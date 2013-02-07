<?php
namespace Magomogo\Model\PropertyContainer;

use Magomogo\Model\PropertyBag;
use Magomogo\Model\Exception\NotFound;

/**
 * This container can keep one model and all its references in memory.
 */
class Memory implements ContainerInterface
{
    /**
     * @var array of PropertyBag
     */
    protected $storage = array();

    /**
     * @var array
     */
    protected $manyToManyReferences = array();

    /**
     * @param \Magomogo\Model\PropertyBag $propertyBag
     * @return \Magomogo\Model\PropertyBag
     * @throws \Magomogo\Model\Exception\NotFound
     */
    public function loadProperties($propertyBag)
    {
        if (!array_key_exists($propertyBag->type(), $this->storage)) {
            throw new NotFound;
        }

        /** @var $properties PropertyBag */
        $properties = $this->storage[$propertyBag->type()][$propertyBag->id];

        foreach ($properties as $name => $property) {
            $propertyBag->$name = $property;
        }

        foreach($properties->exposeReferences() as $referenceName => $referenceProperties) {
            foreach ($referenceProperties as $name => $property) {
                $propertyBag->reference($referenceName)->$name = $property;
            }
        }

        return $propertyBag;
    }

    /**
     * @param \Magomogo\Model\PropertyBag $propertyBag
     * @return \Magomogo\Model\PropertyBag
     */
    public function saveProperties($propertyBag)
    {
        $this->storage[$propertyBag->type()][$propertyBag->id] = $propertyBag;
        foreach ($propertyBag->exposeReferences() as $referenceProperties) {
            $this->saveProperties($referenceProperties);
        }
        return $propertyBag;
    }

    /**
     * @param string $referenceName
     * @param \Magomogo\Model\PropertyBag $leftProperties
     * @param array $connections
     */
    public function referToMany($referenceName, $leftProperties, array $connections)
    {
        $this->manyToManyReferences[$referenceName] = array();
        foreach ($connections as $rightProperties) {
            $this->manyToManyReferences[$referenceName][] = array(
                'left' => $leftProperties,
                'right' => $rightProperties,
            );
            $this->saveProperties($rightProperties);
        }
        $this->saveProperties($leftProperties);
    }

    /**
     * @param string $referenceName
     * @param \Magomogo\Model\PropertyBag $leftProperties
     * @param string $rightPropertiesSample
     * @return array
     */
    public function listReferences($referenceName, $leftProperties, $rightPropertiesSample)
    {
        $connections = array();
        foreach ($this->manyToManyReferences[$referenceName] as $pair) {
            if ($leftProperties === $pair['left']) {
                $connections[] = $pair['right'];
            }
        }

        return $connections;
    }

    /**
     * @param array $propertyBags array of \Magomogo\Model\PropertyBag
     * @return void
     */
    public function deleteProperties(array $propertyBags)
    {
        $this->storage = array();
        $this->manyToManyReferences = array();
    }
}
