<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger\Service\MergeStrategy;

use Creativestyle\CsvMerger\Contracts\Service\MergeStrategyInterface;

class OddPair implements MergeStrategyInterface
{
    /**
     * @inheritdoc
     */
    public function merge(array $leftData, array $rightData)
    {
        $leftDataOdd = isset($leftData[0]) && isset($leftData[1]) && $leftData[0] !== $leftData[1];
        $rightDataOdd = isset($rightData[0]) && isset($rightData[1]) && $rightData[0] !== $rightData[1];
        if ($leftDataOdd === $rightDataOdd || $leftDataOdd === true) {
            return $leftData;
        }
        return $rightData;
    }
}
