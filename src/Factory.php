<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger;

trait Factory
{
    /**
     * @param string $name
     * @return string
     */
    protected function camelize($name)
    {
        $nameParts = explode('_', $name);
        $nameParts = array_map('ucfirst', $nameParts);
        return join('', $nameParts);
    }

    /**
     * @param string $type
     * @return string
     */
    protected function generateTargetClassName($type)
    {
        return preg_replace('/^(.*)Factory$/', sprintf('$1\\%s', $this->camelize($type)), get_called_class());
    }

    /**
     * @param string $className
     * @return bool
     * @throws Exception
     */
    protected function assertClassExists($className)
    {
        if (!class_exists($className)) {
            throw new Exception(sprintf('\'%s\' class does not exist', $className));
        }
        return true;
    }

    /**
     * @param string $className
     * @param string $interface
     * @return bool
     * @throws Exception
     */
    protected function assertClassImplementsInterface($className, $interface)
    {
        if (!in_array($interface, class_implements($className))) {
            throw new Exception(sprintf('\'%s\' class does not implement \'%s\'', $className, $interface));
        }
        return true;
    }
}
