<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger;

class WriterFactory
{
    use Factory;

    /**
     * @param string $type
     * @param DataObject|null $options
     * @return WriterInterface
     */
    public function create($type, DataObject $options = null)
    {
        $className = $this->generateTargetClassName($type);
        $this->assertClassExists($className);
        $this->assertClassImplementsInterface($className, WriterInterface::class);
        return new $className($options ?: new DataObject());
    }
}
