<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger\Contracts\Service;

interface ReaderInterface
{
    /**
     * @param string $path
     * @return \Traversable
     */
    public function read($path);
}
