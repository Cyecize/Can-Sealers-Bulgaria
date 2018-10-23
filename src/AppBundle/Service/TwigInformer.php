<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/5/2018
 * Time: 3:38 PM
 */

namespace AppBundle\Service;

use AppBundle\Constants\Config;
use AppBundle\Constants\ProductType;

class TwigInformer
{
    private $contacts;

    private $websiteInfo;

    private $socialLinks;

    public function __construct(ContactsYamlService $contactsYamlService, WebsiteInfoYamlService $websiteInfo, SocialLinksYamlServiceImpl $socialLinks)
    {
        $this->contacts = $contactsYamlService->getContactsView();
        $this->websiteInfo = $websiteInfo->getWebsiteInfoView();
        $this->socialLinks = $socialLinks->getSocialLinks();
    }

    /**
     * @return \AppBundle\ViewModel\ContactsViewModel
     */
    public function getContacts(): \AppBundle\ViewModel\ContactsViewModel
    {
        return $this->contacts;
    }

    /**
     * @return \AppBundle\ViewModel\WebsiteInfoViewModel
     */
    public function getWebsiteInfo(): \AppBundle\ViewModel\WebsiteInfoViewModel
    {
        return $this->websiteInfo;
    }

    /**
     * @return \AppBundle\ViewModel\SocialLinkViewModel[]|array
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
     * @return array
     */
    public function getProductTypes() : array {
        return ProductType::PRODUCT_TYPES;
    }

    /**
     * @return string
     */
    public function simpleDateFormat(){
        return Config::SIMPLE_DATE_FORMAT;
    }
}