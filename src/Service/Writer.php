<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger\Service;

use Creativestyle\CsvMerger\Contracts\Service\WriterInterface;
use Creativestyle\CsvMerger\Exception;

class Writer implements WriterInterface
{
    /**
     * @param string $path
     * @throws Exception
     */
    protected function createContainerDirectory($path)
    {
        $dirPath = dirname($path);
        if (!file_exists($dirPath)) {
            mkdir($dirPath, 0777, true);
        }
        if (!is_dir($dirPath)) {
            throw new Exception(sprintf('File node with name \'%s\' exists, but is not a directory', $dirPath));
        }
    }

    /**
     * @param array $data
     * @param string $path
     * @return bool
     * @throws Exception
     */
    protected function saveToFile(array $data, $path)
    {
        $handle = fopen($path, 'w');
        if (false === $handle) {
            throw new Exception(sprintf('Cannot open file \'%s\' for writing', $path));
        }
        foreach ($data as $row) {
            fputcsv($handle, $row);
        }
        fclose($handle);
        return true;
    }

    /**
     * @inheritdoc
     */
    public function write(array $data, $file)
    {
        $this->createContainerDirectory($file);
        $this->saveToFile($data, $file);
    }
}
