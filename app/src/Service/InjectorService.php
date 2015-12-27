<?php

namespace PHPMinds\Service;

use Interop\Container\ContainerInterface;
use Slim\Container;

class InjectorService
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param $className
     */
    public function add($className)
    {
        $this->container[$className] = function() use ($className) { return $this->get($className); };
    }

    /**
     * @param $className
     * @return object
     * @throws \Exception
     */
    public function get($className)
    {
        try {

            return (new \ReflectionClass($className))->newInstanceArgs($this->getClassParams($className));

        } catch (\Exception $e) {
            throw $e;

        }
    }

    /**
     * @param $className
     * @return array
     * @throws \Exception
     */
    protected function getClassParams($className)
    {
        $reflection = new \ReflectionMethod($className, '__construct');
        $parameters = $reflection->getParameters();
        $params = [];
        foreach ($parameters as $parameter) {

            if ($this->container->has($parameter->getClass()->name)) {
                $params[] = $this->container[$parameter->getClass()->name];
            } else {
                if (is_null($parameter->getClass()->name)) {
                    throw new \Exception(
                        sprintf(
                            'Could not find a type hint/declaration for the "%s" parameter.',
                            $parameter->getName()
                        )
                    );
                } else {
                    throw new \Exception(
                        sprintf(
                            'Could not find the "%s" class in the container',
                            $parameter->getClass()->name
                        )
                    );
                }
            }
        }

        return $params;
    }
}