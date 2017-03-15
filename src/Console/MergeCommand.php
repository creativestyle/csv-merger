<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger\Console;

use Creativestyle\CsvMerger\DataObject;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MergeCommand extends AbstractCommand
{
    const INPUT_KEY_LEFT_FILE = 'left-file-path';
    const INPUT_KEY_RIGHT_FILE = 'right-file-path';
    const INPUT_KEY_OUTPUT = 'output-path';
    const INPUT_KEY_PREFER_RIGHT = 'prefer-right';
    const INPUT_KEY_SORT = 'sort';

    protected function configure()
    {
        $this->setDescription('Merge two CSV files');
        $this->setDefinition([
            new InputArgument(
                self::INPUT_KEY_LEFT_FILE,
                InputArgument::REQUIRED,
                'Path to the left CSV file'
            ),
            new InputArgument(
                self::INPUT_KEY_RIGHT_FILE,
                InputArgument::REQUIRED,
                'Path to the right CSV file'
            ),
            new InputArgument(
                self::INPUT_KEY_OUTPUT,
                InputArgument::REQUIRED,
                'Path to the output CSV file'
            ),
            new InputOption(
                self::INPUT_KEY_PREFER_RIGHT,
                null,
                InputOption::VALUE_NONE,
                'Prefer values in the right CSV file'
            ),
            new InputOption(
                self::INPUT_KEY_SORT,
                null,
                InputOption::VALUE_NONE,
                'Sort rows in the output CSV file'
            )
        ]);
    }

    /**
     * @param InputInterface $input
     * @return DataObject
     */
    protected function getConfig(InputInterface $input)
    {
        return new DataObject([
            'left_file' => $input->getArgument(self::INPUT_KEY_LEFT_FILE),
            'right_file' => $input->getArgument(self::INPUT_KEY_RIGHT_FILE),
            'output' => $input->getArgument(self::INPUT_KEY_OUTPUT),
            'prefer_right' => $input->getOption(self::INPUT_KEY_PREFER_RIGHT),
            'sort' => $input->getOption(self::INPUT_KEY_SORT)
        ]);
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
            $merger = $this->getCsvMerger();
            switch ($config->getPreferRight()) {
                case true:
                    $merger->merge($config->getRightFile(), $config->getLeftFile(), $config->getOutput());
                    break;
                default:
                    $merger->merge($config->getLeftFile(), $config->getRightFile(), $config->getOutput());
                    break;
            }
            if ($config->getSort()) {
                $sorter = $this->getCsvSorter();
                $sorter->sort($config->getOutput());
            }
            $output->writeln(sprintf(
                '<info>CSV files \'%s\' and \'%s\' merged => %s</info>',
                $config->getLeftFile(),
                $config->getRightFile(),
                $config->getOutput()
            ));
            return 0;
        } catch (\Exception $e) {
            $output->writeln(
                '<error>Error occurred when merging CSV files!</error>' . PHP_EOL . $e->getMessage()
            );
            return $e->getCode();
        }
    }
}
