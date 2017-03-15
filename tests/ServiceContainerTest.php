<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger\Test;

use Creativestyle\CsvMerger\ServiceContainer;

class ServiceContainerTest extends TestCase
{
    public function testItCanBeInstantiated()
    {
        $this->assertInstanceOf(ServiceContainer::class, new ServiceContainer());
    }

    /**
     * @param array $services
     * @dataProvider servicesProvider
     */
    public function testItSetsServiceDefinitionsPassedInConstructorArgument(array $services)
    {
        $serviceContainer = new ServiceContainer($services);
        foreach ($services as $serviceId => $serviceClassName) {
            /** @var \Symfony\Component\DependencyInjection\Definition $serviceDefinition */
            $serviceDefinition = $serviceContainer->getDefinition($serviceId);
            $this->assertSame($serviceClassName, $serviceDefinition->getClass());
            $this->assertTrue($serviceDefinition->isAutowired());
        }
    }

    /**
     * @return array
     */
    public function servicesProvider()
    {
        return [[[
            'service_a' => \stdClass::class,
            'service_b' => \stdClass::class,
            'service_c' => \stdClass::class,
            'service_d' => \stdClass::class
        ]]];
    }
}
