<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger;

class FileScanner
{
    /**
     * @var string
     */
    protected $searchDir;

    /**
     * @var string
     */
    protected $glob;

    /**
     * @var bool
     */
    protected $recursive;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->searchDir = $config->getSearchDir();
        $this->glob = $config->getGlob();
        $this->recursive = $config->isSearchRecursive();
    }

    /**
     * @param string|null $path
     * @param int $flags
     * @return \Generator
     */
    public function scan($path = null, $flags = 0)
    {
        if (null === $path) {
            $path = rtrim($this->searchDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $this->glob;
        }
        foreach (glob($path, $flags) as $file) {
            yield $file;
        }
        if ($this->recursive) {
            foreach (glob(dirname($path) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $directory) {
                $recursivePath = rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . basename($path);
                yield from $this->scan($recursivePath, $flags);
            }
        }
    }
}
