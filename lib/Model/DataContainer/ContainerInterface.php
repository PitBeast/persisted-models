<?php
namespace Model\DataContainer;
use Model\PropertyBag;

interface ContainerInterface
{
    /**
     * @param \Model\PropertyBag $propertyBag
     * @return self
     */
    public function loadProperties(PropertyBag $propertyBag);

    /**
     * @param \Model\PropertyBag $propertyBag
     * @return array unique key
     */
    public function saveProperties(PropertyBag $propertyBag);
}
