<?php
namespace Person;

class Model
{
    /**
     * @var Properties
     */
    private $properties;

    public function __construct(Properties $properties)
    {
        $this->properties = $properties;
    }

    public function politeTitle()
    {
        return $this->properties->title . ' ' . $this->properties->firstName . ' ' . $this->properties->lastName;
    }

    public function contactInfo()
    {
        return 'Phone: ' . $this->properties->phone . "\n" . 'Email: ' . $this->properties->email;
    }

    public function phoneNumberIsChanged($newNumber)
    {
        $this->properties->phone = $newNumber;
    }
}