<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger\Service;

use Creativestyle\CsvMerger\Contracts\Service\MergerInterface;
use Creativestyle\CsvMerger\Contracts\Service\MergeStrategyInterface;
use Creativestyle\CsvMerger\Contracts\Service\ReaderInterface;
use Creativestyle\CsvMerger\Contracts\Service\WriterInterface;

class Merger implements MergerInterface
{
    /**
     * CSV reader instance
     *
     * @var ReaderInterface
     */
    protected $csvReader;

    /**
     * CSV writer instance
     *
     * @var WriterInterface
     */
    protected $csvWriter;

    /**
     * Merge strategy instance
     *
     * @var MergeStrategyInterface
     */
    protected $mergeStrategy;

    /**
     * Merged data array
     *
     * @var array
     */
    protected $data = [];

    /**
     * @param ReaderInterface $csvReader
     * @param WriterInterface $csvWriter
     * @param MergeStrategyInterface $mergeStrategy
     */
    public function __construct(
        ReaderInterface $csvReader,
        WriterInterface $csvWriter,
        MergeStrategyInterface $mergeStrategy
    ) {

        $this->csvReader = $csvReader;
        $this->csvWriter = $csvWriter;
        $this->mergeStrategy = $mergeStrategy;
    }

    /**
     * @param array $row
     * @return $this
     */
    protected function addRow(array $row)
    {
        $key = reset($row);
        if (false !== $key) {
            if (array_key_exists($key, $this->data)) {
                $row = $this->mergeStrategy->merge($this->data[$key], $row);
            }
            $this->data[$key] = $row;
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function merge($leftFile, $rightFile, $outputFile)
    {
        foreach ($this->csvReader->read($leftFile) as $row) {
            $this->addRow($row);
        }
        foreach ($this->csvReader->read($rightFile) as $row) {
            $this->addRow($row);
        }
        $this->csvWriter->write(array_values($this->data), $outputFile);
    }
}
