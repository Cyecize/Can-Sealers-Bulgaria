<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/8/2018
 * Time: 1:27 PM
 */

namespace App\ViewModel;


class ContactsViewModel
{
    private $email;

    private $phoneNumber;

    private $phoneNumber2;

    private $whatsapp;

    public function __construct(array $settings)
    {
        $this->email = $settings['email'];
        $this->phoneNumber = $settings['phoneNumber'];
        $this->phoneNumber2 = $settings['phoneNumber2'];
        $this->whatsapp = $settings['whatsapp'];
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber2()
    {
        return $this->phoneNumber2;
    }

    /**
     * @return mixed
     */
    public function getWhatsapp()
    {
        return $this->whatsapp;
    }
}