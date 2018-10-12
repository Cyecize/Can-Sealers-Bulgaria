<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/5/2018
 * Time: 4:08 PM
 */

namespace AppBundle\Service;

use AppBundle\Constants\Config;

use AppBundle\Service\Lang\ILanguagePack;
use AppBundle\Service\Lang\LanguagePackBG;
use AppBundle\Service\Lang\LanguagePackEN;

class LocalLanguageImpl implements LocalLanguage
{
    private const COOKIE_EXPIRE = 7200; //2HR

    /**
     * @var ILanguagePack
     */
    private $languagePack;

    /**
     * @var string
     */
    private $currentLang;

//    /**
//     * @var LanguageDbService
//     */
//    private $languageDbService;

    public function __construct()
    {
       // $this->languageDbService = $languageDb;
        $this->initLang();
    }

//    public function findCurrentLangs(): array
//    {
//        return $this->languageDbService->findRange(array(Config::COOKIE_NEUTRAL_LANG, $this->currentLang));
//    }

    public function getLocalLang(): string
    {
        return strtolower($this->currentLang);
    }

//    public function findLanguageByName(string $langName): ?Language
//    {
//        return $this->languageDbService->findLangByLocale($langName);
//    }

    public function forName(string $funcName): string
    {
        if (method_exists($this->languagePack, $funcName))
            return $this->languagePack->{$funcName}();
        return $funcName;
    }

    public function setLang(string $langType): void
    {
        switch (strtolower($langType)) {
            case Config::COOKIE_EN_LANG:
                $this->languagePack = new LanguagePackEN();
                break;
            case Config::COOKIE_BG_LANG:
                $this->languagePack = new LanguagePackBG();
                break;
            default:
                $this->languagePack = new LanguagePackBG();
                $langType = Config::COOKIE_BG_LANG;
                break;
        }
        $this->setCookie($langType);
        $this->currentLang = $langType;
    }

    private function initLang(): void
    {
        if (!isset($_COOKIE[Config::COOKIE_LANG_NAME])) {
            $this->languagePack = new LanguagePackBG();
            $this->currentLang = Config::COOKIE_BG_LANG;
            $this->setCookie($this->currentLang);
            return;
        }
        $this->setLang($_COOKIE[Config::COOKIE_LANG_NAME]);
    }

    private function setCookie($lang)
    {
        setcookie(Config::COOKIE_LANG_NAME, $lang, time() + self::COOKIE_EXPIRE, '/');
    }

    public function dictionary(): ILanguagePack
    {
        return $this->languagePack;
    }
}