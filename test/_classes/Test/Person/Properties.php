<?php
namespace Test\Person;

use Magomogo\Persisted\PropertyBag;
use Test\CreditCard\Model as CreditCard;
use Test\CreditCard\Properties as CreditCardProperties;
use Test\Keymarker;

/**
 * @property string $title
 * @property string $firstName
 * @property string $lastName
 * @property string $phone
 * @property string $email
 * @property \Test\CreditCard\Model $creditCard
 */
class Properties extends PropertyBag
{
    /**
     * @var array
     */
    public $tags = array();

    protected function properties()
    {
        return array(
            'title' => '',
            'firstName' => '',
            'lastName' => '',
            'phone' => '',
            'email' => '',
            'creditCard' => new CreditCard(new CreditCardProperties),
            'birthDay' => new \DateTime('1970-01-01')
        );
    }

    /**
     * @param \Magomogo\Persisted\Container\ContainerInterface $container
     * @return self
     */
    public function loadFrom($container)
    {
        $container->loadProperties($this);

        $this->tags = array();
        foreach ($container->listReferences('person2keymarker', $this, new Keymarker\Properties())
                 as $keymarkerProperties) {
            /** @var Keymarker\Properties $keymarkerProperties */
            $this->tags[] = new Keymarker\Model($keymarkerProperties);
        }
        return $this;
    }

    /**
     * @param \Magomogo\Persisted\Container\ContainerInterface $container
     * @return string
     */
    public function putIn($container)
    {
        $container->saveProperties($this);

        $list = array();
        /** @var Keymarker\Model $keymarker */
        foreach ($this->tags as $keymarker) {
            $list[] = $keymarker->propertiesToBeConnectedWith($this);
        }
        $container->referToMany('person2keymarker', $this, $list);

        return $this->id($container);
    }

}