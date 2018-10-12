<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/8/2018
 * Time: 1:41 PM
 */

namespace AppBundle\Service;

use AppBundle\BindingModel\AboutUsBindingModel;
use AppBundle\BindingModel\ContactsBindingModel;
use AppBundle\ViewModel\WebsiteInfoViewModel;

interface WebsiteInfoYamlService
{
    /**
     * @param AboutUsBindingModel $bindingModel
     */
    public function saveAboutUs(AboutUsBindingModel $bindingModel): void;

    /**
     * @param ContactsBindingModel $bindingModel
     */
    public function saveAddress(ContactsBindingModel $bindingModel): void;

    /**
     * @return WebsiteInfoViewModel
     */
    public function getWebsiteInfoView(): WebsiteInfoViewModel;
}