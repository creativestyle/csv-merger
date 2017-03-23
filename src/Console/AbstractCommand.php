<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger\Console;

use Creativestyle\CsvMerger\ServiceContainer;
use Symfony\Component\Console\Command\Command;

abstract class AbstractCommand extends Command
{
    /**
     * @var ServiceContainer|null
     */
    protected $serviceContainer = null;

    /**
     * @return ServiceContainer
     */
    protected function getServiceContainer()
    {
        if (null === $this->serviceContainer) {
            $this->serviceContainer = new ServiceContainer([
                'reader' => \Creativestyle\CsvMerger\Service\Reader::class,
                'writer' => \Creativestyle\CsvMerger\Service\Writer::class,
                'merger' => \Creativestyle\CsvMerger\Service\Merger::class,
                'sanitizer' => \Creativestyle\CsvMerger\Service\Sanitizer::class,
                'sorter' => \Creativestyle\CsvMerger\Service\Sorter::class,
                'merge_strategy' => \Creativestyle\CsvMerger\Service\MergeStrategy\OddPair::class
            ]);
            $this->serviceContainer->compile();
        }
        return $this->serviceContainer;
    }

    /**
     * @return \Creativestyle\CsvMerger\Contracts\Service\MergerInterface
     */
    protected function getCsvMerger()
    {
        return $this->getServiceContainer()->get('merger');
    }

    /**
     * @return \Creativestyle\CsvMerger\Contracts\Service\SorterInterface
     */
    protected function getCsvSorter()
    {
        return $this->getServiceContainer()->get('sorter');
    }

    /**
     * @return \Creativestyle\CsvMerger\Contracts\Service\SanitizerInterface
     */
    protected function getCsvSanitizer()
    {
        return $this->getServiceContainer()->get('sanitizer');
    }
}
