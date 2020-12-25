<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/8/2018
 * Time: 1:23 PM
 */

namespace App\Service;

use App\BindingModel\ContactsBindingModel;
use App\ViewModel\ContactsViewModel;

interface ContactsYamlService
{
    /**
     * @param ContactsBindingModel $bindingModel
     */
    public function saveSettings(ContactsBindingModel $bindingModel) : void ;

    /**
     * @return ContactsViewModel
     */
    public function getContactsView() : ContactsViewModel;

    /**
     * @return array
     */
    public function getContactsSettings() : array ;
}