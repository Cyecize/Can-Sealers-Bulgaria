<?php
/**
 * Created by IntelliJ IDEA.
 * User: tyaki
 * Date: 10/23/2018
 * Time: 11:15 AM
 */

namespace AppBundle\BindingModel;

use Symfony\Component\Validator\Constraints as Assert;

class QuestionBindingModel
{
    /**
     * @Assert\NotBlank(message="fieldCannotBeNull")
     * @Assert\Length(max="50", maxMessage="invalidValue")
     */
    private $fullName;

    /**
     * @Assert\Length(max="50", maxMessage="invalidValue")
     * @Assert\Email(message="invalidValue")
     */
    private $email;

    /**
     * @Assert\NotBlank(message="fieldCannotBeNull")
     * @Assert\Regex(pattern="/^(\+?[0-9]{9,12})$|^.{0}$/", message="invalidValue")
     */
    private $phoneNumber;

    /**
     * @Assert\NotBlank(message="fieldCannotBeNull")
     * @Assert\Length(max="5000", maxMessage="invalidValue")
     */
    private $message;

    /**
     * @Assert\Regex(pattern="/^[0-9]*$/", message="invalidValue")
     */
    private $userId;

    public function __construct()
    {
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
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId == null ? -1 : intval($this->userId);
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }

}