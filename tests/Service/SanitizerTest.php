<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger\Test\Service;

use Creativestyle\CsvMerger\Service\Reader as CsvReader;
use Creativestyle\CsvMerger\Service\Sanitizer as CsvSanitizer;
use Creativestyle\CsvMerger\Service\Writer as CsvWriter;
use Creativestyle\CsvMerger\Test\TestCase;

class SanitizerTest extends TestCase
{
    /**
     * @var CsvSanitizer
     */
    protected $sanitizerInstance;

    protected function setUp()
    {
        parent::setUp();
        $this->sanitizerInstance = new CsvSanitizer(new CsvReader(), new CsvWriter());
    }

    public function testItCanBeInstantiated()
    {
        $this->assertInstanceOf(CsvSanitizer::class, $this->sanitizerInstance);
    }

    public function testItImplementsSanitizerInterface()
    {
        $this->assertInstanceOf(
            \Creativestyle\CsvMerger\Contracts\Service\SanitizerInterface::class,
            $this->sanitizerInstance
        );
    }

    /**
     * @param string $inputFile
     * @param string $outputFile
     * @dataProvider filesForSanitizeProvider
     */
    public function testSanitizeMethodRemoveRowsFromCsvFile($inputFile, $outputFile)
    {
        $inputFileMockPath = $this->mockCsvFileFromFilesystem($this->getFixtureFilePath($inputFile));
        $this->sanitizerInstance->sanitize($inputFileMockPath);
        $this->assertEquals(
            $this->readCsvData($this->getFixtureFilePath($outputFile), true),
            $this->readCsvData($inputFileMockPath, true)
        );
    }
}
