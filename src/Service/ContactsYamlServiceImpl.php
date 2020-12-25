<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/8/2018
 * Time: 1:30 PM
 */

namespace App\Service;

use App\BindingModel\ContactsBindingModel;
use App\Constants\Config;
use App\Utils\YamlParser;
use App\ViewModel\ContactsViewModel;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ContactsYamlServiceImpl implements ContactsYamlService
{
    private const FILE_PATH = "/config/_contacts.yml";

    private $settings;

    private $parameterBag;

    public function __construct(ParameterBagInterface  $parameterBag)
    {
        $this->parameterBag = $parameterBag;
        $this->settings = YamlParser::getFile($this->getFileName());
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
        YamlParser::saveFile($this->settings, $this->getFileName());
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

    private function getFileName() : string {
        return $this->parameterBag->get(Config::KERNEL_PROJECT_DIR) . self::FILE_PATH;
    }
}