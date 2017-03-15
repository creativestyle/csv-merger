<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger\Contracts\Service;

interface WriterInterface
{
    /**
     * @param array $data
     * @param string $file
     * @return void
     */
    public function write(array $data, $file);
}
