<?php
namespace Magomogo\Persisted\Container;

use Magomogo\Persisted\ModelInterface;
use Test\ObjectMother;
use Test\Employee\Model as Employee;
use Test\CreditCard\Model as CreditCard;

class MemoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider modelsProvider
     */
    public function testCanBePutInAndLoadedFrom(ModelInterface $model)
    {
        $container = new Memory;

        $id = $model->propertiesFrom($container)->putIn($container);

        $this->assertEquals(
            $model,
            $model::newProperties($id)->loadFrom($container)->constructModel()
        );
    }

    public static function modelsProvider()
    {
        return array(
            array(ObjectMother\CreditCard::datatransTesting()),
            array(ObjectMother\Person::maxim()),
            array(ObjectMother\Company::xiag()),
            array(ObjectMother\Keymarker::friend()),
            array(ObjectMother\Employee::maxim())
        );
    }

    public function testBehavesCorrectlyWhenEmpty()
    {
        $this->setExpectedException('Magomogo\\Persisted\\Exception\\NotFound');
        Employee::newProperties()->loadFrom(new Memory);
    }

    public function testDelete()
    {
        $container = new Memory;
        $cc = ObjectMother\CreditCard::datatransTesting();
        $cc->propertiesFrom($container)->putIn($container);
        $cc->propertiesFrom($container)->deleteFrom($container);

        $this->setExpectedException('Magomogo\\Persisted\\Exception\\NotFound');
        CreditCard::newProperties()->loadFrom($container);
    }

}
