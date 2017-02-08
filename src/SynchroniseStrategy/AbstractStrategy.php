<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger\SynchroniseStrategy;

abstract class AbstractStrategy
{
    /**
     * @param array $localData
     * @param array $remoteData
     * @return int
     */
    protected function comparePairsDifference(array $localData, array $remoteData)
    {
        $localDifference = isset($localData[0]) && isset($localData[1]) && $localData[0] !== $localData[1];
        $remoteDifference = isset($remoteData[0]) && isset($remoteData[1]) && $remoteData[0] !== $remoteData[1];

        if ($localDifference === $remoteDifference) {
            return 0;
        }
        if ($localDifference === true) {
            return 1;
        }
        if ($localDifference === false) {
            return -1;
        }
    }

    /**
     * @param array $localData
     * @param array $remoteData
     * @return int
     */
    protected function compareLocalPreference(array $localData, array $remoteData)
    {
        $local = isset($localData[0]) && isset($localData[1]);
        $remote = isset($remoteData[0]) && isset($remoteData[1]);
        if ($local) {
            return 1;
        }
        if (!$remote) {
            return 0;
        }
        return -1;
    }

    /**
     * @param array $localData
     * @param array $remoteData
     * @return int
     */
    protected function compareRemotePreference(array $localData, array $remoteData)
    {
        $local = isset($localData[0]) && isset($localData[1]);
        $remote = isset($remoteData[0]) && isset($remoteData[1]);
        if ($remote) {
            return -1;
        }
        if (!$local) {
            return 0;
        }
        return 1;
    }
}
