<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/5/2018
 * Time: 3:38 PM
 */

namespace App\Service;

use App\Constants\Config;
use App\ViewModel\ContactsViewModel;
use App\ViewModel\SocialLinkViewModel;
use App\ViewModel\WebsiteInfoViewModel;

class TwigInformer
{
    private $contacts;

    private $websiteInfo;

    private $socialLinks;

    public function __construct(ContactsYamlService $contactsYamlService,
                                WebsiteInfoYamlService $websiteInfo,
                                SocialLinksYamlServiceImpl $socialLinks)
    {
        $this->contacts = $contactsYamlService->getContactsView();
        $this->websiteInfo = $websiteInfo->getWebsiteInfoView();
        $this->socialLinks = $socialLinks->getSocialLinks();
    }

    /**
     * @return ContactsViewModel
     */
    public function getContacts(): ContactsViewModel
    {
        return $this->contacts;
    }

    /**
     * @return WebsiteInfoViewModel
     */
    public function getWebsiteInfo(): WebsiteInfoViewModel
    {
        return $this->websiteInfo;
    }

    /**
     * @return SocialLinkViewModel[]|array
     */
    public function getSocialLinks()
    {
        return $this->socialLinks;
    }

    /**
     * @return string
     */
    public function getDateFormat() : string {
        return "d/m/Y";
    }

    /**
     * @return string
     */
    public function simpleDateFormat(){
        return Config::SIMPLE_DATE_FORMAT;
    }

    /**
     * @return string
     */
    public function appId() : string {
        return $_ENV[Config::ENV_FB_APP_ID];
    }
}