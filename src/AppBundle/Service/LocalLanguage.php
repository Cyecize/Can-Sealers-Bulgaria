<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/5/2018
 * Time: 4:08 PM
 */

namespace AppBundle\Service;


use AppBundle\Entity\Language;
use AppBundle\Service\Lang\ILanguagePack;

interface LocalLanguage
{
    /**
     * @param string $langType
     */
    public function setLang(string $langType): void;

    /**
     * @return string
     */
    public function getLocalLang(): string;

    /**
     * @param string $funcName
     * @return string
     */
    public function forName(string $funcName): string;

    /**
     * @param string $langName
     * @return Language|null
     */
    public function findLanguageByName(string $langName): ?Language;

    /**
     * @return ILanguagePack
     */
    public function dictionary() : ILanguagePack;

    /**
     * @return Language[]
     */
    public function findCurrentLangs(): array;
}