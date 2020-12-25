<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/8/2018
 * Time: 4:44 PM
 */

namespace App\ViewModel;


class CharacteristicViewModel
{
    private $id;

    private $icon;

    private $title;

    private $body;

    /**
     * CharacteristicViewModel constructor.
     * @param $id
     * @param $icon
     * @param $title
     * @param $body
     */
    public function __construct($id, $icon, $title, $body)
    {
        $this->id = $id;
        $this->icon = $icon;
        $this->title = $title;
        $this->body = $body;
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
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

}