<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/8/2018
 * Time: 2:11 PM
 */

namespace App\Service;


use App\BindingModel\SocialLinkBindingModel;
use App\Constants\Config;
use App\Exception\IllegalArgumentException;
use App\Utils\YamlParser;
use App\ViewModel\SocialLinkViewModel;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class SocialLinksYamlServiceImpl implements SocialLinksYamlService
{
    private const LINK_NOT_FOUND = "Link not found!";
    private const FILE_PATH = "/config/_social-links.yml";

    private $settings;

    private $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
        $this->settings = YamlParser::getFile($this->getFileName());
    }

    public function save(SocialLinkBindingModel $bindingModel): void
    {
        if (!key_exists($bindingModel->getId(), $this->settings))
            throw new IllegalArgumentException(self::LINK_NOT_FOUND);
        $this->settings[$bindingModel->getId()]['icon'] = $bindingModel->getIcon();
        $this->settings[$bindingModel->getId()]['link'] = $bindingModel->getLink();
        YamlParser::saveFile($this->settings, $this->getFileName());
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

    private function getFileName() : string {
        return $this->parameterBag->get(Config::KERNEL_PROJECT_DIR) . self::FILE_PATH;
    }
}