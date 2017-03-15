<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger\Test\Service;

use Creativestyle\CsvMerger\Service\Reader as CsvReader;
use Creativestyle\CsvMerger\Service\Sorter as CsvSorter;
use Creativestyle\CsvMerger\Service\Writer as CsvWriter;
use Creativestyle\CsvMerger\Test\TestCase;

class SorterTest extends TestCase
{
    /**
     * @var CsvSorter
     */
    protected $sorterInstance;

    protected function setUp()
    {
        parent::setUp();
        $this->sorterInstance = new CsvSorter(new CsvReader(), new CsvWriter());
    }

    public function testItCanBeInstantiated()
    {
        $this->assertInstanceOf(CsvSorter::class, $this->sorterInstance);
    }

    public function testItImplementsSorterInterface()
    {
        $this->assertInstanceOf(
            \Creativestyle\CsvMerger\Contracts\Service\SorterInterface::class,
            $this->sorterInstance
        );
    }

    /**
     * @param string $inputFile
     * @param string $outputFile
     * @dataProvider filesForSortProvider
     */
    public function testSortMethodSortsCsvFile($inputFile, $outputFile)
    {
        $inputFileMockPath = $this->mockCsvFileFromFilesystem($this->getFixtureFilePath($inputFile));
        $this->sorterInstance->sort($inputFileMockPath);
        $this->assertEquals(
            $this->readCsvData($this->getFixtureFilePath($outputFile)),
            $this->readCsvData($inputFileMockPath)
        );
    }
}
