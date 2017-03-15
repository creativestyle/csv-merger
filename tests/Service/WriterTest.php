<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger\Test\Service;

use Creativestyle\CsvMerger\Service\Writer as CsvWriter;
use Creativestyle\CsvMerger\Test\TestCase;

class WriterTest extends TestCase
{
    /**
     * @var CsvWriter
     */
    protected $csvWriterInstance;

    protected function setUp()
    {
        parent::setUp();
        $this->csvWriterInstance = new CsvWriter();
    }

    public function testItCanBeInstantiated()
    {
        $this->assertInstanceOf(CsvWriter::class, $this->csvWriterInstance);
    }

    public function testItImplementsWriterInterface()
    {
        $this->assertInstanceOf(
            \Creativestyle\CsvMerger\Contracts\Service\WriterInterface::class,
            $this->csvWriterInstance
        );
    }

    /**
     * @param string $fileName
     * @param array $csvData
     * @dataProvider dataArrayProvider
     */
    public function testWriteMethodWritesCsvFile($fileName, array $csvData)
    {
        $fileMockPath = $this->createFileMock($fileName);
        $this->csvWriterInstance->write($csvData, $fileMockPath);
        $this->assertEquals(
            $csvData,
            $this->readCsvData($fileMockPath)
        );
    }

    /**
     * @param string $fileName
     * @param array $csvData
     * @dataProvider dataArrayProvider
     */
    public function testWriteMethodThrowsExceptionWhenFileIsNotWritable($fileName, array $csvData)
    {
        $fileMockPath = $this->createFileMock($fileName, 0);
        $this->expectException(\Creativestyle\CsvMerger\Exception::class);
        @$this->csvWriterInstance->write($csvData, $fileMockPath);
    }

    /**
     * @param string $fileName
     * @param array $csvData
     * @dataProvider dataArrayProvider
     */
    public function testItCreatesDirectoryIfItDoesNotExist($fileName, array $csvData)
    {
        $directory = 'some_directory';
        $fileMockPath = $this->fsRoot->url() . DIRECTORY_SEPARATOR . $directory . DIRECTORY_SEPARATOR . $fileName;
        $this->csvWriterInstance->write($csvData, $fileMockPath);
        $this->assertTrue($this->fsRoot->hasChild($directory));
    }

    /**
     * @param string $fileName
     * @param array $csvData
     * @dataProvider dataArrayProvider
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function testItThrowsExceptionWhenWriteDirectoryIsRegularFile($fileName, array $csvData)
    {
        $directory = 'some_directory';
        $invalidDirectoryMockPath = $this->createFileMock($directory);
        $this->expectException(\Creativestyle\CsvMerger\Exception::class);
        $this->csvWriterInstance->write($csvData, $invalidDirectoryMockPath . DIRECTORY_SEPARATOR . $fileName);
    }
}
