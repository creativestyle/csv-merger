<?php
/**
 * CSV Merger
 *
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace Creativestyle\CsvMerger;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ServiceContainer extends ContainerBuilder
{
    /**
     * @param array $services
     * @param ParameterBagInterface|null $parameterBag
     */
    public function __construct(array $services = [], ParameterBagInterface $parameterBag = null)
    {
        parent::__construct($parameterBag);
        foreach ($services as $serviceKey => $serviceClass) {
            $serviceDefinition = new Definition($serviceClass);
            $serviceDefinition->setAutowired(true);
            $this->setDefinition($serviceKey, $serviceDefinition);
        }
    }
}
