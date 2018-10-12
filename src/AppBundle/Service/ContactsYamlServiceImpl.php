<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/8/2018
 * Time: 1:30 PM
 */

namespace AppBundle\Service;


use AppBundle\BindingModel\ContactsBindingModel;
use AppBundle\Utils\YamlParser;
use AppBundle\ViewModel\ContactsViewModel;

class ContactsYamlServiceImpl implements ContactsYamlService
{
    private const FILE_PATH = "../app/config/_contacts.yml";

    private $settings;

    public function __construct(LocalLanguage $localLanguage)
    {
        $this->settings = YamlParser::getFile(self::FILE_PATH);
    }

    /**
     * @param ContactsBindingModel $bindingModel
     */
    public function saveSettings(ContactsBindingModel $bindingModel): void
    {
        $reflection = new \ReflectionObject($bindingModel);
        foreach ($this->settings['contacts'] as $key => $setting) {
            $property  = $reflection->getProperty($key);
            $property->setAccessible(true);
            $this->settings['contacts'][$key] = $property->getValue($bindingModel);
        }
        YamlParser::saveFile($this->settings, self::FILE_PATH);
    }

    /**
     * @return ContactsViewModel
     */
    public function getContactsView(): ContactsViewModel
    {
        return new ContactsViewModel($this->settings['contacts']);
    }

    /**
     * @return array
     */
    public function getContactsSettings(): array
    {
        return $this->settings;
    }
}