<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger\Test\Service;

use Creativestyle\CsvMerger\Service\Merger as CsvMerger;
use Creativestyle\CsvMerger\Service\MergeStrategy\OddPair as MergeStrategy;
use Creativestyle\CsvMerger\Service\Reader as CsvReader;
use Creativestyle\CsvMerger\Service\Writer as CsvWriter;
use Creativestyle\CsvMerger\Test\TestCase;

class MergerTest extends TestCase
{
    /**
     * @var CsvMerger
     */
    protected $mergerInstance;

    protected function setUp()
    {
        parent::setUp();
        $this->mergerInstance = new CsvMerger(new CsvReader(), new CsvWriter(), new MergeStrategy());
    }

    public function testItCanBeInstantiated()
    {
        $this->assertInstanceOf(CsvMerger::class, $this->mergerInstance);
    }

    public function testItImplementsMergerInterface()
    {
        $this->assertInstanceOf(
            \Creativestyle\CsvMerger\Contracts\Service\MergerInterface::class,
            $this->mergerInstance
        );
    }

    /**
     * @param string $leftFile
     * @param string $rightFile
     * @param string $mergedFile
     * @dataProvider filesForMergeProvider
     */
    public function testMergeMethodMergesTwoCsvFiles($leftFile, $rightFile, $mergedFile)
    {
        $leftFileMockPath = $this->mockCsvFileFromFilesystem($this->getFixtureFilePath($leftFile));
        $rightFileMockPath = $this->mockCsvFileFromFilesystem($this->getFixtureFilePath($rightFile));
        $mergedFileMockPath = $this->fsRoot->url() . DIRECTORY_SEPARATOR . $mergedFile;
        $this->mergerInstance->merge($leftFileMockPath, $rightFileMockPath, $mergedFileMockPath);
        $this->assertEquals(
            $this->readCsvData($this->getFixtureFilePath($mergedFile), true),
            $this->readCsvData($mergedFileMockPath, true)
        );
    }
}
