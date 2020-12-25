<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/8/2018
 * Time: 1:42 PM
 */

namespace App\Service;


use App\BindingModel\AboutUsBindingModel;
use App\BindingModel\ContactsBindingModel;
use App\Utils\YamlParser;
use App\ViewModel\WebsiteInfoViewModel;

class WebsiteInfoYamlServiceImpl implements WebsiteInfoYamlService
{
    private const FILE_DIR = "../config/";

    private $settings;

    private $fileDir;

    public function __construct(LocalLanguage $localLanguage)
    {
        $this->fileDir = self::FILE_DIR . "_info-" . $localLanguage->getLocalLang() . ".yml";
        $this->settings = YamlParser::getFile($this->fileDir);
    }

    public function saveAboutUs(AboutUsBindingModel $bindingModel): void
    {
        $this->settings['aboutUs'] = $bindingModel->getAboutUs();
        YamlParser::saveFile($this->settings, $this->fileDir);
    }

    public function saveAddress(ContactsBindingModel $bindingModel): void
    {
        $this->settings['address'] = $bindingModel->getAddress();
        YamlParser::saveFile($this->settings, $this->fileDir);
    }

    /**
     * @return WebsiteInfoViewModel
     */
    public function getWebsiteInfoView(): WebsiteInfoViewModel
    {
        return new WebsiteInfoViewModel($this->settings);
    }
}