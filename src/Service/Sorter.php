<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger\Service;

use Creativestyle\CsvMerger\Contracts\Service\ReaderInterface;
use Creativestyle\CsvMerger\Contracts\Service\SorterInterface;
use Creativestyle\CsvMerger\Contracts\Service\WriterInterface;

class Sorter implements SorterInterface
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
     * Data array
     *
     * @var array
     */
    protected $data = [];

    /**
     * @param ReaderInterface $csvReader
     * @param WriterInterface $csvWriter
     */
    public function __construct(
        ReaderInterface $csvReader,
        WriterInterface $csvWriter
    ) {

        $this->csvReader = $csvReader;
        $this->csvWriter = $csvWriter;
    }

    /**
     * @param array $row
     * @return $this
     */
    protected function addRow(array $row)
    {
        $key = reset($row);
        if (false !== $key) {
            $this->data[$key] = $row;
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function sort($file)
    {
        foreach ($this->csvReader->read($file) as $row) {
            $this->addRow($row);
        }
        ksort($this->data, SORT_STRING);
        $this->csvWriter->write(array_values($this->data), $file);
    }
}
