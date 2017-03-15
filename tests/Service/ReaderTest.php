<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger\Test\Service;

use Creativestyle\CsvMerger\Service\Reader as CsvReader;
use Creativestyle\CsvMerger\Test\TestCase;

class ReaderTest extends TestCase
{
    /**
     * @var CsvReader
     */
    protected $csvReaderInstance;

    protected function setUp()
    {
        parent::setUp();
        $this->csvReaderInstance = new CsvReader();
    }

    public function testItCanBeInstantiated()
    {
        $this->assertInstanceOf(CsvReader::class, $this->csvReaderInstance);
    }

    public function testItImplementsReaderInterface()
    {
        $this->assertInstanceOf(
            \Creativestyle\CsvMerger\Contracts\Service\ReaderInterface::class,
            $this->csvReaderInstance
        );
    }

    /**
     * @param string $fileName
     * @param array $csvData
     * @dataProvider dataArrayProvider
     */
    public function testReadMethodReadsCsvFile($fileName, array $csvData)
    {
        $filePath = $this->mockCsvFile($fileName, $csvData);
        foreach ($this->csvReaderInstance->read($filePath) as $row) {
            $rowIndex = array_search($row, $csvData);
            unset($csvData[$rowIndex]);
        }
        $this->assertEmpty($csvData);
    }

    /**
     * @param string $fileName
     * @dataProvider dataArrayProvider
     */
    public function testReadMethodThrowsExceptionIfFileDoesNotExist($fileName)
    {
        $this->expectException(\Creativestyle\CsvMerger\Exception::class);
        @$this->csvReaderInstance->read($this->createFileMock($fileName, 0))->current();
    }
}
