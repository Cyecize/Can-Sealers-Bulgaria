<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/17/2018
 * Time: 4:37 PM
 */

namespace AppBundle\BindingModel;


use Symfony\Component\Validator\Constraints as Assert;

class PersonalInfoBindingModel
{

    /**
     * @Assert\Regex(pattern="/^(\+?[0-9]{9,12})$|^.{0}$/", message="invalidValue")
     */
    private $phoneNumber;

    /**
     * @Assert\Length(max="90", maxMessage="invalidValue")
     */
    private $fullName;

    public function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param mixed $phoneNumber
     */
    public function setPhoneNumber($phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param mixed $fullName
     */
    public function setFullName($fullName): void
    {
        $this->fullName = $fullName;
    }
}