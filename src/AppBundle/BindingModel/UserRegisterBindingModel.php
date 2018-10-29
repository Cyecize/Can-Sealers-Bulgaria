<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/8/2018
 * Time: 11:02 AM
 */

namespace AppBundle\BindingModel;


use Symfony\Component\Validator\Constraints as Assert;

class UserRegisterBindingModel
{
    /**
     * @Assert\NotNull(message="fieldCannotBeNull")
     * @Assert\NotBlank(message="fieldCannotBeNull")
     * @Assert\Length(max="25", min="1", maxMessage="invalidValue", minMessage="invalidValue")
     * @Assert\Regex(pattern="/^[a-zA-Z]{1,}[\w-_0-9]*$/", message="invalidValue")
     */
    private $username;

    /**
     * @Assert\NotNull(message="fieldCannotBeNull")
     * @Assert\Email(message="invalidValue")
     */
    private $email;

    /**
     * @Assert\NotBlank(message="fieldCannotBeNull")
     * @Assert\Length(min="6", minMessage="passwordLengthInvalid")
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="passwordsDoNotMatch")
     */
    private $confPassword;

    /**
     * @Assert\Regex(pattern="/^(\+?[0-9]{9,12})$|^.{0}$/", message="invalidValue")
     */
    private $phoneNumber;

    /**
     * @Assert\Length(max="50", maxMessage="invalidValue")
     */
    private $fullName;

    /**
     * @Assert\NotBlank(message="fieldCannotBeNull")
     * @Assert\Regex(pattern="/^(bg|en)$/", message="invalidValue")
     */
    private $locale;

    public function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getConfPassword()
    {
        return $this->confPassword;
    }

    /**
     * @param mixed $confPassword
     */
    public function setConfPassword($confPassword): void
    {
        $this->confPassword = $confPassword;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param mixed $phoneNumber
     */
    public function setPhoneNumber($phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param mixed $fullName
     */
    public function setFullName($fullName): void
    {
        $this->fullName = $fullName;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param mixed $locale
     */
    public function setLocale($locale): void
    {
        $this->locale = $locale;
    }

}