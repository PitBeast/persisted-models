<?php
namespace Test\ObjectMother;

use Model\DataContainer\ArrayMap;
use CreditCard\Properties;
use CreditCard\Model;

class CreditCard
{
    public static function datatransTesting()
    {
        $properties = new Properties();
        $properties->loadFrom(
            new ArrayMap(array(
                'system' => 'VISA',
                'pan' => '9500000000000001',
                'validMonth' => '12',
                'validYear' => '2015',
                'ccv' => '234',
                'cardholderName' => 'Maxim Gnatenko'
            ))
        );

        return new Model($properties);
    }

}
