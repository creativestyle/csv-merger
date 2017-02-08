<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger;

interface SynchroniseStrategyInterface
{
    /**
     * @param array $localData
     * @param array $remoteData
     * @return array
     */
    public function synchronise(array $localData, array $remoteData);
}
