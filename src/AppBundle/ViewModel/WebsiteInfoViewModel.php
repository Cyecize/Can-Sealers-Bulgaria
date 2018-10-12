<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/8/2018
 * Time: 1:41 PM
 */

namespace AppBundle\ViewModel;


class WebsiteInfoViewModel
{
    private $aboutUs;

    private $address;

    private $location;

    public function __construct(array $settings)
    {
        $this->aboutUs = $settings['aboutUs'];
        $this->address = $settings['address'];
        $this->location = $settings['location'];
    }

    /**
     * @return mixed
     */
    public function getAboutUs()
    {
        return $this->aboutUs;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }
}