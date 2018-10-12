<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/8/2018
 * Time: 2:11 PM
 */

namespace AppBundle\Service;


use AppBundle\BindingModel\SocialLinkBindingModel;
use AppBundle\Exception\IllegalArgumentException;
use AppBundle\Utils\YamlParser;
use AppBundle\ViewModel\SocialLinkViewModel;

class SocialLinksYamlServiceImpl implements SocialLinksYamlService
{
    private const LINK_NOT_FOUND = "Link not found!";
    private const FILE_PATH = "../app/config/_social-links.yml";

    private $settings;

    public function __construct()
    {
        $this->settings = YamlParser::getFile(self::FILE_PATH);
    }

    public function save(SocialLinkBindingModel $bindingModel): void
    {
        if (!key_exists($bindingModel->getId(), $this->settings))
            throw new IllegalArgumentException(self::LINK_NOT_FOUND);
        $this->settings[$bindingModel->getId()]['icon'] = $bindingModel->getIcon();
        $this->settings[$bindingModel->getId()]['link'] = $bindingModel->getLink();
        YamlParser::saveFile($this->settings, self::FILE_PATH);
    }

    public function findOneById(int $id): ?SocialLinkViewModel
    {
        if (!key_exists($id, $this->settings))
            return null;
        return new SocialLinkViewModel($id, $this->settings[$id]['icon'], $this->settings[$id]['link']);
    }

    public function getSocialLinks(): array
    {
        $res = array();
        foreach ($this->settings as $index => $setting)
            $res[] = new SocialLinkViewModel($index, $setting['icon'], $setting['link']);
        return $res;
    }
}