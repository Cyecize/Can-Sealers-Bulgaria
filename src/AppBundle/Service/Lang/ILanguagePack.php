<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/5/2018
 * Time: 4:23 PM
 */

namespace AppBundle\Service\Lang;


interface ILanguagePack
{
    function getLocalLang(): string;

    function home(): string;

    function products(): string;

    function contacts(): string;

    function login(): string;

    function register(): string;

    function callNow(): string;

    function welcomeMessage(): string;

    function weGuarantee(): string;

    function categories(): string;

    function address(): string;

    function sendQuestion(): string;

    function yourName(): string;

    function yourPhoneNumber(): string;

    function yourQuestion(): string;

    function send(): string;

    function aboutUs(): string;

    function websiteName(): string;

    function username(): string;

    function email(): string;

    function password(): string;

    function go(): string;

    function logout(): string;

    function fieldCannotBeNull(): string;

    function invalidValue(): string;

    function passwordLengthInvalid(): string;

    function passwordsDoNotMatch(): string;

    function usernameAlreadyTaken(): string;

    function emailAlreadyInUse(): string;

    function all(): string;

    function category(): string;

    function details(): string;

    function currency(): string;

    function emptyCategory(): string;

    function categoryNotFoundFormat(): string;

    function ourCanSealers(): string;

    function next(): string;

    function previous(): string;

    function productNotFound(): string;

    function price(): string;

    function registeredOn(): string;

    function profile(): string;

    function changePassword(): string;

    function changePersonalInfo(): string;

    function removeAccount(): string;

    function privateInfo(): string;

    function phoneNumber(): string;

    function name(): string;

    function optional() : string ;

    function usernameOrEmailDoesNotExist() : string ;

    function invalidPassword() : string ;

    function save() : string ;
}