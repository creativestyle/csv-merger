<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger\Contracts\Service;

interface SorterInterface
{
    /**
     * @param string $file
     * @return void
     */
    public function sort($file);
}
