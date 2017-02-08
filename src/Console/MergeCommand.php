<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger\Console;

use Creativestyle\CsvMerger\Config;
use Creativestyle\CsvMerger\DataObject;
use Creativestyle\CsvMerger\Exception;
use Creativestyle\CsvMerger\FileScanner;
use Creativestyle\CsvMerger\ReaderFactory;
use Creativestyle\CsvMerger\Service\CsvMerger;
use Creativestyle\CsvMerger\SynchroniseStrategyFactory;
use Creativestyle\CsvMerger\WriterFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MergeCommand extends Command
{
    const INPUT_KEY_OUTPUT = 'output-path';
    const INPUT_KEY_CSV_DIR = 'search-directory';
    const INPUT_KEY_GLOB = 'file-pattern';
    const INPUT_KEY_NO_RECURSIVE = 'no-recursive';

    public function __construct($name = null)
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setDescription('Merge CSV files into one file');
        $this->setDefinition([
            new InputArgument(
                self::INPUT_KEY_CSV_DIR,
                InputArgument::REQUIRED,
                'Path to CSV files search directory'
            ),
            new InputArgument(
                self::INPUT_KEY_OUTPUT,
                InputArgument::REQUIRED,
                'Path to the output file'
            ),
            new InputOption(
                self::INPUT_KEY_GLOB,
                '-g',
                InputOption::VALUE_REQUIRED,
                'CSV files search pattern',
                '*.csv'
            ),
            new InputOption(
                self::INPUT_KEY_NO_RECURSIVE,
                '-r',
                InputOption::VALUE_NONE,
                'Do NOT search recursively'
            )
        ]);
    }

    /**
     * @param InputInterface $input
     * @return Config
     */
    protected function getConfig(InputInterface $input)
    {
        $configParams = new DataObject([
            'output' => $input->getArgument(self::INPUT_KEY_OUTPUT),
            'search_dir' => $input->getArgument(self::INPUT_KEY_CSV_DIR),
            'glob' => $input->getOption(self::INPUT_KEY_GLOB),
            'no_recursive' => $input->getOption(self::INPUT_KEY_NO_RECURSIVE)
        ]);
        return new Config($configParams);
    }

    protected function getCsvWriter()
    {
        $writerFactory = new WriterFactory();
        return $writerFactory->create('csv');
    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return null|int null or 0 if everything went fine, or an error code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $config = $this->getConfig($input);
            $csvMerger = new CsvMerger(
                $config,
                new ReaderFactory(),
                new SynchroniseStrategyFactory(),
                new FileScanner($config)
            );
            $writer = $this->getCsvWriter();
            $csvData = $csvMerger->merge();
            $writer->write($csvData, $config->getOutputPath());
            return 0;
        } catch (Exception $e) {
            $output->writeln(
                '<error>Error occurred when building MTF test case!</error>' . PHP_EOL . $e->getMessage()
            );
            return $e->getCode();
        }
    }
}
