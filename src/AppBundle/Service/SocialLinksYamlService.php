<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/8/2018
 * Time: 2:09 PM
 */

namespace AppBundle\Service;


use AppBundle\BindingModel\SocialLinkBindingModel;
use AppBundle\Exception\IllegalArgumentException;
use AppBundle\ViewModel\SocialLinkViewModel;

interface SocialLinksYamlService
{
    /**
     * @param SocialLinkBindingModel $bindingModel
     * @throws IllegalArgumentException
     */
    public function save(SocialLinkBindingModel $bindingModel) : void ;
    /**
     * @param int $id
     * @return SocialLinkViewModel|null
     */
    public function findOneById(int $id) : ?SocialLinkViewModel;

    /**
     * @return SocialLinkViewModel[]
     */
    public function getSocialLinks() : array ;
}