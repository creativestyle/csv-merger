<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger\Contracts\Service;

interface MergeStrategyInterface
{
    /**
     * @param array $leftData
     * @param array $rightData
     * @return array
     */
    public function merge(array $leftData, array $rightData);
}
