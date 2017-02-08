<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger;

class Config
{
    /**
     * @var string|null
     */
    protected $outputPath;

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
     * @var string
     */
    protected $synchroniseStrategy;

    /**
     * @param DataObject $options
     */
    public function __construct(DataObject $options)
    {
        $this->outputPath = $options->getData('output');
        $this->searchDir = $options->getData('search_dir');
        $this->glob = $options->getData('glob');
        $this->recursive = !(bool)$options->getData('no_recursive');
        $this->synchroniseStrategy = 'pairs_difference_local_preference';
    }

    /**
     * @return string|null
     */
    public function getOutputPath()
    {
        return $this->outputPath;
    }

    /**
     * @return bool
     */
    public function outputToConsole()
    {
        return empty($this->outputPath);
    }

    /**
     * @return string
     */
    public function getSearchDir()
    {
        return $this->searchDir;
    }

    /**
     * @return string
     */
    public function getGlob()
    {
        return $this->glob;
    }

    /**
     * @return bool
     */
    public function isSearchRecursive()
    {
        return $this->recursive;
    }

    public function getSynchroniseStrategy()
    {
        return $this->synchroniseStrategy;
    }
}
