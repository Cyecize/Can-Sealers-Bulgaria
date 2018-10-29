<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/5/2018
 * Time: 4:09 PM
 */

namespace AppBundle\Service;


use AppBundle\Entity\Language;

interface LanguageDbService
{
    /**
     * Creates initial languages
     */
    public function initLanguages(): void;

    /**
     * @param int $id
     * @return Language|null
     */
    public function findLangById(int $id): ?Language;

    /**
     * @param string $locale
     * @return Language|null
     */
    public function findLangByLocale(string $locale): ?Language;

    /**
     * @return Language[]
     */
    public function findAll(): array;

    /**
     * @param array $langs
     * @return Language[]
     */
    public function findRange(array $langs) : array ;
}