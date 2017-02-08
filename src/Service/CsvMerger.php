<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger\Service;

use Creativestyle\CsvMerger\Config;
use Creativestyle\CsvMerger\FileScanner;
use Creativestyle\CsvMerger\ReaderFactory;
use Creativestyle\CsvMerger\SynchroniseStrategyFactory;

class CsvMerger
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var \Creativestyle\CsvMerger\ReaderInterface
     */
    protected $csvReader;

    /**
     * @var \Creativestyle\CsvMerger\SynchroniseStrategyInterface
     */
    protected $synchroniseStrategy;

    /**
     * @var FileScanner
     */
    protected $fileScanner;

    /**
     * Merged data array
     *
     * @var array
     */
    protected $data = [];

    public function __construct(
        Config $config,
        ReaderFactory $readerFactory,
        SynchroniseStrategyFactory $synchroniseFactory,
        FileScanner $fileScanner
    ) {
        $this->config = $config;
        $this->csvReader = $readerFactory->create('csv');
        $this->synchroniseStrategy = $synchroniseFactory->create(
            $config->getSynchroniseStrategy()
        );
        $this->fileScanner = $fileScanner;
    }

    /**
     * @param array $row
     * @return array
     */
    protected function sanitizeRow(array $row)
    {
        return [
            isset($row[0]) ? $row[0] : null,
            isset($row[1]) ? $row[1] : null
        ];
    }

    /**
     * @param array $row
     * @return $this
     */
    protected function addRow(array $row)
    {
        $key = $row[0];
        if (null !== $key) {
            if (array_key_exists($key, $this->data)) {
                $row = $this->synchroniseStrategy->synchronise(
                    [$key, $this->data[$key]],
                    $row
                );
            }
            $this->data[$key] = $row[1];
        }
        return $this;
    }

    /**
     * @return \Generator
     */
    protected function getInputFiles()
    {
        $outputPath = $this->config->getOutputPath();
        if ($outputPath && file_exists($outputPath)) {
            yield $outputPath;
        }
        yield from $this->fileScanner->scan();
    }

    /**
     * @return array
     */
    public function merge()
    {
        foreach ($this->getInputFiles() as $path) {
            foreach ($this->csvReader->read($path) as $row) {
                $row = $this->sanitizeRow($row);
                $this->addRow($row);
            }
        }
        return array_map(
            function ($key, $value) {
                return [$key, $value];
            },
            array_keys($this->data),
            $this->data
        );
    }
}
