<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger;

interface WriterInterface
{
    /**
     * @param array $data
     * @param string $file
     * @return bool
     */
    public function write(array $data, $file);
}
