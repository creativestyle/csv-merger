<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger\Contracts\Service;

interface MergerInterface
{
    /**
     * @param string $leftFile
     * @param string $rightFile
     * @param string $outputFile
     * @return void
     */
    public function merge($leftFile, $rightFile, $outputFile);
}
