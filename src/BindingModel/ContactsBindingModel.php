<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/8/2018
 * Time: 1:25 PM
 */

namespace App\BindingModel;

use Symfony\Component\Validator\Constraints as Assert;

class ContactsBindingModel
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    private $address;

    /**
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/[\+0-9]{9,11}/")
     * @Assert\Length(max="15")
     */
    private $phoneNumber;

    /**
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/[\+0-9]{9,11}/")
     * @Assert\Length(max="15")
     */
    private $phoneNumber2;

    /**
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/(([+][(]?[0-9]{1,3}[)]?)|([(]?[0-9]{4}[)]?))\s*[)]?[-\s\.]?[(]?[0-9]{1,3}[)]?([-\s\.]?[0-9]{3})([-\s\.]?[0-9]{3,4})/")
     * @Assert\Length(max="15")
     */
    private $whatsapp;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Assert\Length(max="50")
     */
    private $email;

    public function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address): void
    {
        $this->address = $address;
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
    public function getPhoneNumber2()
    {
        return $this->phoneNumber2;
    }

    /**
     * @param mixed $phoneNumber2
     */
    public function setPhoneNumber2($phoneNumber2): void
    {
        $this->phoneNumber2 = $phoneNumber2;
    }

    /**
     * @return mixed
     */
    public function getWhatsapp()
    {
        return $this->whatsapp;
    }

    /**
     * @param mixed $whatsapp
     */
    public function setWhatsapp($whatsapp): void
    {
        $this->whatsapp = $whatsapp;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }
}