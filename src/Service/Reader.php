<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger\Service;

use Creativestyle\CsvMerger\Contracts\Service\ReaderInterface;
use Creativestyle\CsvMerger\Exception;

class Reader implements ReaderInterface
{
    /**
     * @param string $path
     * @return \Generator
     * @throws Exception
     */
    public function read($path)
    {
        $handle = fopen($path, 'r');
        if (false === $handle) {
            throw new Exception(sprintf('Cannot open file \'%s\' for reading', $path));
        }
        while (false !== ($row = fgetcsv($handle))) {
            yield $row;
        }
        fclose($handle);
    }
}
