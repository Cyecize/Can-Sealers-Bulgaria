<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/5/2018
 * Time: 4:23 PM
 */

namespace App\Service\Lang;

use App\Constants\Config;

class LanguagePackEN implements ILanguagePack
{
    public const HOME = "Home";
    public const PRODUCTS = "Products";
    public const CONTACTS = "Contacts";
    public const LOGIN = "Login";
    public const REGISTER = "Register";
    public const CALL_NOW = "Call now:";
    public const WELCOME_MESSAGE = "Make your life more delicious";
    public const WE_GUARANTEE = "We Guarantee";
    public const CATEGORIES = "Categories:";
    public const ADDRESS = "Address";
    public const SEND_QUESTION = "Ask us anything";
    public const YOUR_NAME = "Your name";
    public const YOUR_PHONE_NUMBER = "Your phone number";
    public const YOUR_QUESTION = "Your question";
    public const SEND = "Send";
    public const ABOUT_US = "About us";
    public const WEBSITE_NAME = "Can Sealers BG";
    public const USERNAME = "Username";
    public const EMAIL = "E-Mail";
    public const PASSWORD = "Password";
    public const GO = "Go";
    public const LOGOUT = "Logout";
    public const FIELD_CANNOT_BE_NULL = "Field cannot be empty.";
    public const INVALID_VALUE = "Invalid value.";
    public const PASSWORDS_DO_NOT_MATCH = "Passwords do not match.";
    public const PASSWORD_LENGTH_INVALID = "Password should be at least 6 characters long.";
    public const EMAIL_ALREADY_IN_USE = "Е-Mail address taken!";
    public const USERNAME_ALREADY_TAKEN = "Username is taken!";
    public const ALL = "All";
    public const CATEGORY = "Category";
    public const DETAILS = "Details";
    public const CURRENCY = "BGN";
    public const EMPTY_CATEGORY = "Empty Category.";
    public const CAT_NOT_FOUND_FORMAT = "Category with name %s was not found.";
    public const OUR_CAN_SEALERS = "Our Products";
    public const PREVIOUS = "Previous";
    public const NEXT = "Next";
    public const PRODUCT_NOT_FOUND = "Product Not Found!";
    public const PRICE = "Price";
    public const REGISTERED_ON = "Registered On";
    public const PROFILE = "Profile";
    public const CHANGE_PASSWORD = "Change Password";
    public const CHANGE_PERSONAL_INFO = "Personal Info";
    public const REMOVE_ACCOUNT = "Remove Account";
    public const PRIVATE_INFO = "Hidden";
    public const PHONE_NUMBER = "Phone Number";
    public const NAME = "Name";
    public const OPTIONAL = "Optional";
    public const USERNAME_OR_EMAIL_DOES_NOT_EXIST = "Username or E-Mail does not exist!";
    public const INVALID_PASSWORD = "Invalid Password!";
    public const SAVE = "Save";
    public const ADMIN_PANEL = "Admin Panel";
    public const ADD_PRODUCT = "New Product";
    public const SHOW_ALL = "Show All";
    public const EDIT = "Edit";
    public const USERS = "Users";
    public const REMOVE = "Remove";
    public const CREATE_CATEGORY = "Create Category";
    public const NAME_TAKEN = "Name already in use";
    public const YOUR_MESSAGE_WAS_SENT = "Your message was sent.";
    public const VIEW_FULL_SCREEN = "Show on full screen";
    public const REMOVE_ALL = "Clear all";
    public const NO_NOTIFICATIONS = "All Good!";
    public const QUESTIONS = "Questions";
    public const NOTIFICATIONS = "Notifications";
    public const NOTIFY_ALL = "Notify All";
    public const USER_NOT_FOUND_FORMAT = "User with username %s does not exist!";
    public const FORGOTTEN_PASSWORD = "Forgotten password";
    public const SHARE = "Share";
    public const LANGUAGE = "Language";
    public const PRODUCT_CODE = "Product Code";
    public const RECIPES = "Recipes";
    public const ADD_RECEIPT = "New Recipe";
    public const TAX_INCLUDED = "VAT included";
    public const TAX_NOT_INCLUDED = "VAT not included";
    public const VIDEO = "Video";

    public function video(): string
    {
        return self::VIDEO;
    }

    public function taxIncluded(): string
    {
        return self::TAX_INCLUDED;
    }

    public function taxNotIncluded(): string
    {
        return self::TAX_NOT_INCLUDED;
    }

    public function addReceipt(): string
    {
        return self::ADD_RECEIPT;
    }

    public function recipes(): string
    {
        return self::RECIPES;
    }

    public function productCode(): string
    {
        return self::PRODUCT_CODE;
    }

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

    function getLocalLang(): string
    {
        return Config::COOKIE_EN_LANG;
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