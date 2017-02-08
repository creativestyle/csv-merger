<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger;

class DataObject
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * @param string $name
     * @return string
     */
    private function underscore($name)
    {
        return strtolower(trim(preg_replace('/([A-Z]|[0-9]+)/', "_$1", $name), '_'));
    }

    /**
     * @param array $data
     * @return $this
     */
    public function addData(array $data)
    {
        foreach ($data as $index => $value) {
            $this->setData($index, $value);
        }
        return $this;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function setData($key, $value = null)
    {
        $this->data[$key] = $value;
        return $this;
    }

    /**
     * @param string|null $key
     * @return $this
     */
    public function unsetData($key = null)
    {
        if ($key === null) {
            $this->data = [];
            return $this;
        }
        if (isset($this->data[$key]) || array_key_exists($key, $this->data)) {
            unset($this->data[$key]);
        }
        return $this;
    }

    /**
     * @param string|null $key
     * @return mixed
     */
    public function getData($key = null)
    {
        if (null === $key) {
            return $this->data;
        }
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }
        return null;
    }

    /**
     * @param string|null $key
     * @return bool
     */
    public function hasData($key = null)
    {
        if (null === $key) {
            return !empty($this->data);
        }
        return array_key_exists($key, $this->data);
    }

    /**
     * @param string $method
     * @param array $args
     * @return mixed
     * @throws Exception
     */
    public function __call($method, $args)
    {
        switch (substr($method, 0, 3)) {
            case 'get':
                $key = $this->underscore(substr($method, 3));
                return $this->getData($key);
            case 'set':
                $key = $this->underscore(substr($method, 3));
                $value = isset($args[0]) ? $args[0] : null;
                return $this->setData($key, $value);
            case 'uns':
                $key = $this->underscore(substr($method, 3));
                return $this->unsetData($key);
            case 'has':
                $key = $this->underscore(substr($method, 3));
                return $this->hasData($key);
        }
        throw new Exception('Invalid method %1::%2', [get_class($this), $method]);
    }
}
