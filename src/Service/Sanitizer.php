<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger\Service;

use Creativestyle\CsvMerger\Contracts\Service\ReaderInterface;
use Creativestyle\CsvMerger\Contracts\Service\SanitizerInterface;
use Creativestyle\CsvMerger\Contracts\Service\WriterInterface;

class Sanitizer implements SanitizerInterface
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
     * @param array $row
     * @return bool
     */
    protected function shouldAddRow(array $row)
    {
        return isset($row[0]) && isset($row[1]) && $row[1] !== $row[0];
    }

    /**
     * @inheritdoc
     */
    public function sanitize($file)
    {
        foreach ($this->csvReader->read($file) as $row) {
            if ($this->shouldAddRow($row)) {
                $this->addRow($row);
            }
        }
        $this->csvWriter->write(array_values($this->data), $file);
    }
}
