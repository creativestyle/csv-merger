<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger;

use Creativestyle\CsvMerger\Console\MergeCommand;
use Symfony\Component\Console\Application;

class Console extends Application
{
    /**
     * @param string $name The name of the application
     * @param string $version The version of the application
     */
    public function __construct(
        $name = 'CSV Merger',
        $version = 'UNKNOWN'
    ) {
        parent::__construct($name, $version);
        $this->add(new MergeCommand('merge'));
    }
}
