<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger\Test\Service\MergeStrategy;

use Creativestyle\CsvMerger\Service\MergeStrategy\OddPair as OddPairStrategy;
use Creativestyle\CsvMerger\Test\TestCase;

class OddPairTest extends TestCase
{
    /**
     * @var OddPairStrategy
     */
    protected $strategyInstance;

    protected function setUp()
    {
        parent::setUp();
        $this->strategyInstance = new OddPairStrategy();
    }

    public function testItCanBeInstantiated()
    {
        $this->assertInstanceOf(OddPairStrategy::class, $this->strategyInstance);
    }

    public function testItImplementsMergeStrategyInterface()
    {
        $this->assertInstanceOf(
            \Creativestyle\CsvMerger\Contracts\Service\MergeStrategyInterface::class,
            $this->strategyInstance
        );
    }

    /**
     * @param array $leftData
     * @param array $rightData
     * @param array $expectedMergedData
     * @dataProvider dataToMergeProvider
     */
    public function testMergeMethodReturnsMergedArray(array $leftData, array $rightData, array $expectedMergedData)
    {
        $this->assertSame($expectedMergedData, $this->strategyInstance->merge($leftData, $rightData));
    }

    /**
     * @return array
     */
    public function dataToMergeProvider()
    {
        return [
            [
                ['Left data original value', 'Left data original value'],
                ['Right data original value', 'Right data original value'],
                ['Left data original value', 'Left data original value']
            ],
            [
                ['Left data original value', 'Left data original value'],
                ['Right data original value', 'Right data modified value'],
                ['Right data original value', 'Right data modified value'],
            ],
            [
                ['Left data original value', 'Left data modified value'],
                ['Right data original value', 'Right data original value'],
                ['Left data original value', 'Left data modified value'],
            ],
            [
                ['Left data original value', 'Left data modified value'],
                ['Right data original value', 'Right data modified value'],
                ['Left data original value', 'Left data modified value'],
            ]
        ];
    }
}
