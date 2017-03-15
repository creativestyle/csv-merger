<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger\Test;

use Creativestyle\CsvMerger\DataObject;

class DataObjectTest extends TestCase
{
    /**
     * @var DataObject
     */
    protected $dataObjectInstance;

    protected function setUp()
    {
        parent::setUp();
        $this->dataObjectInstance = new DataObject();
    }

    protected function tearDown()
    {
        unset($this->dataObjectInstance);
    }

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

    public function testItCanBeInstantiated()
    {
        $this->assertInstanceOf(DataObject::class, $this->dataObjectInstance);
    }

    public function testItHasNoDataWhenInstantiatedWithNoArguments()
    {
        $this->assertFalse($this->dataObjectInstance->hasData());
        $this->assertEmpty($this->dataObjectInstance->getData());
    }

    /**
     * @param array $data
     * @dataProvider allKeyValuePairsProvider
     */
    public function testItHasDataWhenInstantiatedWithDataArgument(array $data)
    {
        $dataObject = new DataObject($data);
        foreach ($data as $key => $value) {
            $this->assertTrue($dataObject->hasData($key));
            $this->assertSame($value, $dataObject->getData($key));
        }
    }

    /**
     * @param array $data
     * @dataProvider allKeyValuePairsProvider
     */
    public function testAddDataMethodSetsAllValues(array $data)
    {
        $this->dataObjectInstance->addData($data);
        foreach ($data as $key => $value) {
            $this->assertTrue($this->dataObjectInstance->hasData($key));
            $this->assertSame($value, $this->dataObjectInstance->getData($key));
        }
    }

    /**
     * @param string $key
     * @param string $value
     * @dataProvider keyValuePairProvider
     */
    public function testSetDataMethodSetsValueForSelectedKey($key, $value)
    {
        $this->dataObjectInstance->setData($key, $value);
        $this->assertTrue($this->dataObjectInstance->hasData($key));
        $this->assertSame($value, $this->dataObjectInstance->getData($key));
    }

    /**
     * @param string $key
     * @param string $value
     * @dataProvider keyValuePairProvider
     */
    public function testUnsetDataMethodClearsValueForSelectedKey($key, $value)
    {
        $this->dataObjectInstance->setData($key, $value);
        $this->dataObjectInstance->unsetData($key);
        $this->assertFalse($this->dataObjectInstance->hasData($key));
        $this->assertNull($this->dataObjectInstance->getData($key));
    }

    /**
     * @param array $data
     * @dataProvider allKeyValuePairsProvider
     */
    public function testUnsetDataMethodWithNoParametersClearsAllValues(array $data)
    {
        $this->dataObjectInstance->addData($data);
        $this->dataObjectInstance->unsetData();
        $this->assertFalse($this->dataObjectInstance->hasData());
        $this->assertEmpty($this->dataObjectInstance->getData());
    }

    /**
     * @param array $data
     * @dataProvider allKeyValuePairsProvider
     */
    public function testGetDataMethodWithNoParametersReturnsArrayOfAllValues(array $data)
    {
        $this->dataObjectInstance->addData($data);
        $this->assertSame($data, $this->dataObjectInstance->getData());
    }

    /**
     * @param string $key
     * @dataProvider nonExistentKeyProvider
     */
    public function testGetDataMethodReturnsNullForNonExistentKey($key)
    {
        $this->assertNull($this->dataObjectInstance->getData($key));
    }

    /**
     * @param string $key
     * @param string $value
     * @dataProvider keyValuePairProvider
     */
    public function testHasDataMethodReturnTrueIfValueForSelectedKeyExists($key, $value)
    {
        $this->dataObjectInstance->setData($key, $value);
        $this->assertTrue($this->dataObjectInstance->hasData($key));
    }

    /**
     * @param string $key
     * @dataProvider nonExistentKeyProvider
     */
    public function testHasDataMethodReturnFalseForNonExistentKey($key)
    {
        $this->assertFalse($this->dataObjectInstance->hasData($key));
    }

    /**
     * @param array $data
     * @dataProvider allKeyValuePairsProvider
     */
    public function testHasDataMethodWithNoParametersReturnsTrueIfAnyValueExists(array $data)
    {
        $this->dataObjectInstance->addData($data);
        $this->assertTrue($this->dataObjectInstance->hasData());
    }

    /**
     * @param string $key
     * @param string $value
     * @dataProvider keyValuePairProvider
     */
    public function testDataValuesCanBeManipulatedThroughMagicMethod($key, $value)
    {
        $camelizedKey = $this->camelize($key);
        $hasDataMagicMethod = sprintf('has%s', $camelizedKey);
        $setDataMagicMethod = sprintf('set%s', $camelizedKey);
        $getDataMagicMethod = sprintf('get%s', $camelizedKey);
        $unsetDataMagicMethod = sprintf('uns%s', $camelizedKey);
        $this->assertFalse($this->dataObjectInstance->$hasDataMagicMethod());
        $this->dataObjectInstance->$setDataMagicMethod($value);
        $this->assertTrue($this->dataObjectInstance->$hasDataMagicMethod());
        $this->assertSame($value, $this->dataObjectInstance->$getDataMagicMethod());
        $this->dataObjectInstance->$unsetDataMagicMethod();
        $this->assertFalse($this->dataObjectInstance->$hasDataMagicMethod());
    }

    /**
     * @param string $methodName
     * @dataProvider notImplementedMethodProvider
     */
    public function testCallToNotImplementedMethodThrowsException($methodName)
    {
        $this->expectException(\Creativestyle\CsvMerger\Exception::class);
        $this->dataObjectInstance->$methodName();
    }

    /**
     * @return array
     */
    public function keyValuePairProvider()
    {
        return [
            ['null_sample', null],
            ['integer_sample', 42],
            ['float_sample', 3.14159265359],
            ['string_sample', 'Lorem ipsum dolor sit amet'],
            ['utf8_string_sample', 'Zażółć gęślą jaźń'],
            ['object_sample', new \stdClass()],
            ['array_of_integers_sample', [0, 1, 1, 2, 3, 5, 8, 13, 21, 34, 55, 89]],
            ['array_of_floats_sample', [3.14159265359, 2.71828182846]],
            ['array_of_strings_sample', ['lorem', 'ipsum', 'dolor', 'sit', 'amet']],
            ['array_of_objects_sample', [new \stdClass(), new \stdClass()]],
            ['mixed_array_sample', [3.14159265359, 'zażółć', 42, 'lorem', new \stdClass()]],
        ];
    }

    /**
     * @return array
     */
    public function allKeyValuePairsProvider()
    {
        $keyValuePairs = $this->keyValuePairProvider();
        return [[array_combine(array_column($keyValuePairs, 0), array_column($keyValuePairs, 1))]];
    }

    /**
     * @return array
     */
    public function nonExistentKeyProvider()
    {
        return [
            ['non_existent_key'],
            ['second_non_existent_key'],
            ['another_non_existent_key']
        ];
    }

    /**
     * @return array
     */
    public function notImplementedMethodProvider()
    {
        return [
            ['notImplementedMethod'],
            ['secondNotImplementedMethod'],
            ['anotherNotImplementedMethod']
        ];
    }
}
