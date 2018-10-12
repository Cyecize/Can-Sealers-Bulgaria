<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/8/2018
 * Time: 2:09 PM
 */

namespace AppBundle\ViewModel;


class SocialLinkViewModel
{
    private $icon;

    private $id;

    private $link;

    /**
     * SocialLinkViewModel constructor.
     * @param $icon
     * @param $id
     * @param $link
     */
    public function __construct($id, $icon, $link)
    {
        $this->icon = $icon;
        $this->id = $id;
        $this->link = $link;
    }

    /**
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }
}