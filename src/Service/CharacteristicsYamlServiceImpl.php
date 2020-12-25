<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/8/2018
 * Time: 4:48 PM
 */

namespace App\Service;


use App\BindingModel\CharacteristicBindingModel;
use App\Constants\Config;
use App\Exception\IllegalArgumentException;
use App\Utils\YamlParser;
use App\ViewModel\CharacteristicViewModel;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CharacteristicsYamlServiceImpl implements CharacteristicsYamlService
{
    private const CHARACTERISTIC_NOT_FOUND = "Characteristic not found!";

    private const FILE_DIR = "/config/";

    private $settings;

    private $fileDir;

    private $parameterBag;

    private $localLanguage;

    public function __construct(LocalLanguage $localLanguage, ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
        $this->localLanguage = $localLanguage;
        $this->settings = YamlParser::getFile($this->getFileName());
    }

    public function saveCharacteristic(CharacteristicBindingModel $bindingModel): void
    {
        if ($this->findOneById($bindingModel->getId()) == null)
            throw new IllegalArgumentException(self::CHARACTERISTIC_NOT_FOUND);
        $reflection = new \ReflectionObject($bindingModel);
        foreach ($this->settings[$bindingModel->getId()] as $key => $value) {
            $property = $reflection->getProperty($key);
            $property->setAccessible(true);
            $this->settings[$bindingModel->getId()][$key] = $property->getValue($bindingModel);
        }
        YamlParser::saveFile($this->settings, $this->fileDir);
    }

    public function findOneById($id): ?CharacteristicViewModel
    {
        if (!key_exists($id, $this->settings))
            return null;
        $setting = $this->settings[$id];
        return new CharacteristicViewModel($id, $setting['icon'], $setting['title'], $setting['body']);
    }

    public function getCharacteristics(): array
    {
        $res = array();
        foreach ($this->settings as $key => $setting)
            $res[] = $this->findOneById($key);
        return $res;
    }

    private function getFileName(): string
    {
        return $this->fileDir = $this->parameterBag->get(Config::KERNEL_PROJECT_DIR)
            . self::FILE_DIR
            . "_characteristics-"
            . $this->localLanguage->getLocalLang()
            . ".yml";
    }
}