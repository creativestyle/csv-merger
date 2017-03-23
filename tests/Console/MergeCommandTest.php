<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger\Test\Console;

use Creativestyle\CsvMerger\Console\MergeCommand;
use Creativestyle\CsvMerger\Test\TestCase;

class MergeCommandTest extends TestCase
{
    /**
     * @var MergeCommand
     */
    protected $commandInstance;

    protected function setUp()
    {
        parent::setUp();
        $this->commandInstance = new MergeCommand('test_command');
    }

    /**
     * @param string $leftFilePath
     * @param string $rightFilePath
     * @param string $outputPath
     * @param bool $preferRight
     * @param bool $sort
     * @param bool $sanitize
     * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\Console\Input\InputInterface
     */
    protected function getInputMock(
        $leftFilePath,
        $rightFilePath,
        $outputPath,
        $preferRight = false,
        $sort = false,
        $sanitize = false
    ) {
        $inputMock = $this->getMockBuilder(\Symfony\Component\Console\Input\InputInterface::class)
            ->getMock();

        $argumentsMap = [
            [MergeCommand::INPUT_KEY_LEFT_FILE, $leftFilePath],
            [MergeCommand::INPUT_KEY_RIGHT_FILE, $rightFilePath],
            [MergeCommand::INPUT_KEY_OUTPUT, $outputPath]
        ];

        $optionsMap = [
            [MergeCommand::INPUT_KEY_PREFER_RIGHT, $preferRight],
            [MergeCommand::INPUT_KEY_SORT, $sort],
            [MergeCommand::INPUT_KEY_SANITIZE, $sanitize]
        ];

        $inputMock->method('getArgument')
            ->will($this->returnValueMap($argumentsMap));

        $inputMock->method('getOption')
            ->will($this->returnValueMap($optionsMap));

        return $inputMock;
    }

    /**
     * @return \Symfony\Component\Console\Output\OutputInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getOutputMock()
    {
        $outputMock = $this->getMockBuilder(\Symfony\Component\Console\Output\OutputInterface::class)
            ->getMock();
        return $outputMock;
    }

    public function testItCanBeInstantiated()
    {
        $this->assertInstanceOf(MergeCommand::class, $this->commandInstance);
    }

    /**
     * @param string $leftFile
     * @param string $rightFile
     * @param string $mergedFile
     * @param bool $preferRightFile
     * @dataProvider filesForMergeProvider
     */
    public function testItMergesTwoCsvFiles($leftFile, $rightFile, $mergedFile, $preferRightFile = false)
    {
        $leftFileMockPath = $this->mockCsvFileFromFilesystem($this->getFixtureFilePath($leftFile));
        $rightFileMockPath = $this->mockCsvFileFromFilesystem($this->getFixtureFilePath($rightFile));
        $mergedFileMockPath = $this->fsRoot->url() . DIRECTORY_SEPARATOR . $mergedFile;

        $commandExitCode = $this->commandInstance->run(
            $this->getInputMock($leftFileMockPath, $rightFileMockPath, $mergedFileMockPath, $preferRightFile),
            $this->getOutputMock()
        );

        $this->assertEquals(0, $commandExitCode);
        $this->assertTrue($this->fsRoot->hasChild($mergedFile));
        $this->assertEquals(
            $this->readCsvData($this->getFixtureFilePath($mergedFile), true),
            $this->readCsvData($mergedFileMockPath, true)
        );
    }

    /**
     * @param string $leftFile
     * @param string $rightFile
     * @param string $mergedFile
     * @param bool $preferRightFile
     * @dataProvider filesForMergeProvider
     */
    public function testItMergesAndSortsTwoCsvFiles($leftFile, $rightFile, $mergedFile, $preferRightFile = false)
    {
        $leftFileMockPath = $this->mockCsvFileFromFilesystem($this->getFixtureFilePath($leftFile));
        $rightFileMockPath = $this->mockCsvFileFromFilesystem($this->getFixtureFilePath($rightFile));
        $mergedFileMockPath = $this->fsRoot->url() . DIRECTORY_SEPARATOR . $mergedFile;

        $commandExitCode = $this->commandInstance->run(
            $this->getInputMock($leftFileMockPath, $rightFileMockPath, $mergedFileMockPath, $preferRightFile, true),
            $this->getOutputMock()
        );

        $this->assertEquals(0, $commandExitCode);
        $this->assertTrue($this->fsRoot->hasChild($mergedFile));
        $this->assertEquals(
            $this->readCsvData($this->getFixtureFilePath($mergedFile)),
            $this->readCsvData($mergedFileMockPath)
        );
    }

    /**
     * @param string $leftFile
     * @param string $rightFile
     * @param string $mergedFile
     * @param bool $preferRightFile
     * @dataProvider filesForMergeAndSanitizeProvider
     */
    public function testItMergesAndSanitizesTwoCsvFiles($leftFile, $rightFile, $mergedFile, $preferRightFile = false)
    {
        $leftFileMockPath = $this->mockCsvFileFromFilesystem($this->getFixtureFilePath($leftFile));
        $rightFileMockPath = $this->mockCsvFileFromFilesystem($this->getFixtureFilePath($rightFile));
        $mergedFileMockPath = $this->fsRoot->url() . DIRECTORY_SEPARATOR . $mergedFile;

        $commandExitCode = $this->commandInstance->run(
            $this->getInputMock(
                $leftFileMockPath,
                $rightFileMockPath,
                $mergedFileMockPath,
                $preferRightFile,
                false,
                true
            ),
            $this->getOutputMock()
        );

        $this->assertEquals(0, $commandExitCode);
        $this->assertTrue($this->fsRoot->hasChild($mergedFile));
        $this->assertEquals(
            $this->readCsvData($this->getFixtureFilePath($mergedFile), true),
            $this->readCsvData($mergedFileMockPath, true)
        );
    }

    /**
     * @param string $leftFile
     * @param string $rightFile
     * @param string $mergedFile
     * @dataProvider filesForMergeProvider
     */
    public function testItMergesTwoCsvFilesAndPrefersRightFile($leftFile, $rightFile, $mergedFile)
    {
        $this->testItMergesTwoCsvFiles($rightFile, $leftFile, $mergedFile, true);
    }

    /**
     * @param string $leftFile
     * @param string $rightFile
     * @param string $mergedFile
     * @dataProvider filesForMergeProvider
     */
    public function testItMergesAndSortsTwoCsvFilesAndPrefersRightFile($leftFile, $rightFile, $mergedFile)
    {
        $this->testItMergesAndSortsTwoCsvFiles($rightFile, $leftFile, $mergedFile, true);
    }

    public function testItCatchesExceptionThrownDuringRun()
    {
        $this->assertNotEquals(0, $this->commandInstance->run(
            $this->getInputMock(null, null, null),
            $this->getOutputMock()
        ));
    }
}
