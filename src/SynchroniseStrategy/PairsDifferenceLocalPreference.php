<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger\SynchroniseStrategy;

use Creativestyle\CsvMerger\SynchroniseStrategyInterface;

class PairsDifferenceLocalPreference extends AbstractStrategy implements SynchroniseStrategyInterface
{
    /**
     * @param array $localData
     * @param array $remoteData
     * @return array
     */
    public function synchronise(array $localData, array $remoteData)
    {
        switch ($this->comparePairsDifference($localData, $remoteData)) {
            case 1:
                return $localData;
            case -1:
                return $remoteData;
        }
        if ($this->compareLocalPreference($localData, $remoteData) === -1) {
            return $remoteData;
        }
        return $localData;
    }
}
