<?php
namespace Test\Company;

use Magomogo\Persisted\Container\ContainerInterface;
use Magomogo\Persisted\ModelInterface;
use Test\JobRecord;

class Model implements ModelInterface
{
    /**
     * @var Properties
     */
    private $properties;

    /**
     * @param ContainerInterface $container
     * @param string $id
     * @return self
     */
    public static function load($container, $id)
    {
        $p = new Properties();
        $p->persisted($id, $container);
        return new self($p->loadFrom($container));
    }

    public function save($container)
    {
        return $this->properties->putIn($container);
    }

//----------------------------------------------------------------------------------------------------------------------

    public function __construct($properties)
    {
        $this->properties = $properties;
    }

    public function name()
    {
        return $this->properties->name;
    }

    /**
     * @param JobRecord\Properties $jobRecordProperties
     * @param string $type
     * @return $this
     */
    public function connectToAJobRecord($jobRecordProperties, $type)
    {
        $jobRecordProperties->foreign()->$type = $this->properties;
        return $this;
    }
}