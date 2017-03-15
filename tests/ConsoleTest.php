<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger\Test;

use Creativestyle\CsvMerger\Console;

class ConsoleTest extends TestCase
{
    protected $availableCommands = [
        'merge' => \Creativestyle\CsvMerger\Console\MergeCommand::class
    ];

    /**
     * @var Console
     */
    protected $consoleApp;

    protected function setUp()
    {
        parent::setUp();
        $this->consoleApp = new Console();
    }

    public function testItCanBeInstantiated()
    {
        $this->assertInstanceOf(Console::class, $this->consoleApp);
    }

    public function testItHandlesAllAvailableCommands()
    {
        foreach ($this->availableCommands as $commandName => $commandClassName) {
            $this->assertInstanceOf($commandClassName, $this->consoleApp->get($commandName));
        }
    }
}
