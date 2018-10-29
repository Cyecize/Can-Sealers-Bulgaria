<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/5/2018
 * Time: 4:23 PM
 */

namespace AppBundle\Service\Lang;

use AppBundle\Constants\Config;

class LanguagePackBG implements ILanguagePack
{
    public const HOME = "Начало";
    public const PRODUCTS = "Продукти";
    public const CONTACTS = "Контакти";
    public const LOGIN = "Вход";
    public const REGISTER = "Регистрация";
    public const CALL_NOW = "Обадете се сега:";
    public const WELCOME_MESSAGE = "С Нас Живота Ви Става По-Вкусен";
    public const WE_GUARANTEE = "Ние Гарантираме";
    public const CATEGORIES = "Категории:";
    public const ADDRESS = "Адрес";
    public const SEND_QUESTION = "Изпратете бързо запитване";
    public const YOUR_NAME = "Вашето име";
    public const YOUR_PHONE_NUMBER = "Вашия тел. номер";
    public const YOUR_QUESTION = "Вашето запитване";
    public const SEND = "Изпращане";
    public const ABOUT_US = "За нас";
    public const WEBSITE_NAME = "Затварачки за консервни кутии";
    public const USERNAME = "Потр. име";
    public const EMAIL = "E-Mail";
    public const PASSWORD = "Парола";
    public const GO = "Напред";
    public const LOGOUT = "Изход";
    public const FIELD_CANNOT_BE_NULL = "Полето не може да е празно";
    public const INVALID_VALUE = "Невалидна стойност";
    public const PASSWORDS_DO_NOT_MATCH = "Паролите не съвпадат";
    public const PASSWORD_LENGTH_INVALID = "Паролата трябва да е поне 6 знака";
    public const USERNAME_ALREADY_TAKEN = "Потр. име е заето!";
    public const EMAIL_ALREADY_IN_USE = "Е-Mail адреса е зает!";
    public const ALL = "Всички";
    public const CATEGORY = "Категория";
    public const DETAILS = "Детайли";
    public const CURRENCY = "лв.";
    public const EMPTY_CATEGORY = "Категорията е празна.";
    public const CAT_NOT_FOUND_FORMAT = "Категория с име %s не беше намерена.";
    public const OUR_CAN_SEALERS = "Нашите Продукти";
    public const PREVIOUS = "Предишна";
    public const NEXT = "Следваща";
    public const PRODUCT_NOT_FOUND = "Продуктът не е намерен!";
    public const PRICE = "Цена";
    public const PROFILE = "Профил";
    public const REGISTERED_ON = "Регистриран на";
    public const CHANGE_PASSWORD = "Смяна на парола";
    public const CHANGE_PERSONAL_INFO = "Смяна на лична информация";
    public const REMOVE_ACCOUNT = "Премахване на профил";
    public const PRIVATE_INFO = "Скрито";
    public const PHONE_NUMBER = "Тел. номер";
    public const NAME = "Име";
    public const OPTIONAL = "По Желание";
    public const USERNAME_OR_EMAIL_DOES_NOT_EXIST = "Потребител с това потр. име или Е-Mail не е намерен!";
    public const INVALID_PASSWORD = "Неправилна парола!";
    public const SAVE = "Запазване";
    public const ADMIN_PANEL = "Администраторски панел";
    public const ADD_PRODUCT = "Нов продукт";
    public const SHOW_ALL = "Покажи всички";
    public const EDIT = "Редакция";
    public const USERS = "Потребители";
    public const REMOVE = "Премахване";
    public const CREATE_CATEGORY = "Създаване на категория";
    public const NAME_TAKEN = "Името е заето";
    public const YOUR_MESSAGE_WAS_SENT = "Вашето запитване беше изпратено.";
    public const VIEW_FULL_SCREEN = "Показване на цял екран";
    public const REMOVE_ALL = "Премахване на всички";
    public const NO_NOTIFICATIONS = "Няма нови известия!";
    public const QUESTIONS = "Въпроси";
    public const NOTIFICATIONS = "Известия";
    public const NOTIFY_ALL = "Извести всички";
    public const USER_NOT_FOUND_FORMAT = "Потребител с потр. име %s не е намерен!";
    public const FORGOTTEN_PASSWORD = "Забравена парола";
    public const SHARE = "Споделяне";
    public const LANGUAGE = "Език";

    public function language(): string
    {
        return self::LANGUAGE;
    }

    public function share(): string
    {
       return self::SHARE;
    }

    public function forgottenPassword(): string
    {
       return self::FORGOTTEN_PASSWORD;
    }

    public function userNotFoundFormat(): string
    {
       return self::USER_NOT_FOUND_FORMAT;
    }

    function questions(): string
    {
        return self::QUESTIONS;
    }

    function notifications(): string
    {
        return self::NOTIFICATIONS;
    }

    function notifyAll(): string
    {
       return self::NOTIFY_ALL;
    }

    function viewFullScreen(): string
    {
        return self::VIEW_FULL_SCREEN;
    }

    function removeAll(): string
    {
        return self::REMOVE_ALL;
    }

    function noNotifications(): string
    {
        return self::NO_NOTIFICATIONS;
    }

    public function yourMessageWasSent(): string
    {
        return self::YOUR_MESSAGE_WAS_SENT;
    }

    function createCategory(): string
    {
        return self::CREATE_CATEGORY;
    }

    function nameTaken(): string
    {
        return self::NAME_TAKEN;
    }

    public function remove(): string
    {
        return self::REMOVE;
    }

    function adminPanel(): string
    {
        return self::ADMIN_PANEL;
    }

    function addProduct(): string
    {
        return self::ADD_PRODUCT;
    }

    function showAll(): string
    {
        return self::SHOW_ALL;
    }

    function edit(): string
    {
        return self::EDIT;
    }

    function users(): string
    {
        return self::USERS;
    }

    public function save(): string
    {
        return self::SAVE;
    }

    public function invalidPassword(): string
    {
        return self::INVALID_PASSWORD;
    }

    public function usernameOrEmailDoesNotExist(): string
    {
        return self::USERNAME_OR_EMAIL_DOES_NOT_EXIST;
    }

    public function optional(): string
    {
        return self::OPTIONAL;
    }

    function changePassword(): string
    {
        return self::CHANGE_PASSWORD;
    }

    function changePersonalInfo(): string
    {
        return self::CHANGE_PERSONAL_INFO;
    }

    function removeAccount(): string
    {
        return self::REMOVE_ACCOUNT;
    }

    function privateInfo(): string
    {
        return self::PRIVATE_INFO;
    }

    function phoneNumber(): string
    {
        return self::PHONE_NUMBER;
    }

    function name(): string
    {
        return self::NAME;
    }

    public function profile(): string
    {
        return self::PROFILE;
    }

    public function registeredOn(): string
    {
        return self::REGISTERED_ON;
    }

    public function price(): string
    {
        return self::PRICE;
    }

    public function productNotFound(): string
    {
        return self::PRODUCT_NOT_FOUND;
    }

    public function previous(): string
    {
        return self::PREVIOUS;
    }

    public function next(): string
    {
        return self::NEXT;
    }

    public function ourCanSealers(): string
    {
        return self::OUR_CAN_SEALERS;
    }

    public function categoryNotFoundFormat(): string
    {
        return self::CAT_NOT_FOUND_FORMAT;
    }

    public function emptyCategory(): string
    {
        return self::EMPTY_CATEGORY;
    }

    public function currency(): string
    {
        return self::CURRENCY;
    }

    public function details(): string
    {
        return self::DETAILS;
    }

    public function category(): string
    {
        return self::CATEGORY;
    }

    public function all(): string
    {
        return self::ALL;
    }

    public function usernameAlreadyTaken(): string
    {
        return self::USERNAME_ALREADY_TAKEN;
    }

    public function emailAlreadyInUse(): string
    {
        return self::EMAIL_ALREADY_IN_USE;
    }

    function fieldCannotBeNull(): string
    {
        return self::FIELD_CANNOT_BE_NULL;
    }

    function invalidValue(): string
    {
        return self::INVALID_VALUE;
    }

    function passwordLengthInvalid(): string
    {
        return self::PASSWORD_LENGTH_INVALID;
    }

    function passwordsDoNotMatch(): string
    {
        return self::PASSWORDS_DO_NOT_MATCH;
    }

    function logout(): string
    {
        return self::LOGOUT;
    }

    public function getLocalLang(): string
    {
        return Config::COOKIE_BG_LANG;
    }

    function username(): string
    {
        return self::USERNAME;
    }

    function email(): string
    {
        return self::EMAIL;
    }

    function password(): string
    {
        return self::PASSWORD;
    }

    function go(): string
    {
        return self::GO;
    }

    public function websiteName(): string
    {
        return self::WEBSITE_NAME;
    }

    function home(): string
    {
        return self::HOME;
    }

    function products(): string
    {
        return self::PRODUCTS;
    }

    function contacts(): string
    {
        return self::CONTACTS;
    }

    function login(): string
    {
        return self::LOGIN;
    }

    function register(): string
    {
        return self::REGISTER;
    }

    function callNow(): string
    {
        return self::CALL_NOW;
    }

    function welcomeMessage(): string
    {
        return self::WELCOME_MESSAGE;
    }

    function weGuarantee(): string
    {
        return self::WE_GUARANTEE;
    }

    function categories(): string
    {
        return self::CATEGORIES;
    }

    function address(): string
    {
        return self::ADDRESS;
    }

    function sendQuestion(): string
    {
        return self::SEND_QUESTION;
    }

    function yourName(): string
    {
        return self::YOUR_NAME;
    }

    function yourPhoneNumber(): string
    {
        return self::YOUR_PHONE_NUMBER;
    }

    function yourQuestion(): string
    {
        return self::YOUR_QUESTION;
    }

    function send(): string
    {
        return self::SEND;
    }

    function aboutUs(): string
    {
        return self::ABOUT_US;
    }
}