<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notification
 *
 * @ORM\Table(name="notifications")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NotificationRepository")
 */
class Notification
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="username_id", type="integer", nullable=true)
     */
    private $usernameId;

    /**
     * @var string
     *
     * @ORM\Column(name="full_name", type="string", length=50)
     */
    private $fullName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_number", type="string", length=15)
     */
    private $phoneNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     */
    private $message;

    /**
     * @var int
     *
     * @ORM\Column(name="target_id", type="integer")
     */
    private $targetId;

    /**
     * @var bool
     *
     * @ORM\Column(name="seen", type="boolean")
     */
    private $seen;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    private $about;

    public function __construct()
    {
        $this->date= new \DateTime('now', new \DateTimeZone('Europe/Sofia'));
        $this->seen = false;
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set usernameId
     *
     * @param integer $usernameId
     *
     * @return Notification
     */
    public function setUsernameId($usernameId)
    {
        $this->usernameId = $usernameId;

        return $this;
    }

    /**
     * Get usernameId
     *
     * @return int
     */
    public function getUsernameId()
    {
        return $this->usernameId;
    }

    /**
     * Set fullName
     *
     * @param string $fullName
     *
     * @return Notification
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Notification
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return Notification
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return Notification
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set targetId
     *
     * @param integer $targetId
     *
     * @return Notification
     */
    public function setTargetId($targetId)
    {
        $this->targetId = $targetId;

        return $this;
    }

    /**
     * Get targetId
     *
     * @return int
     */
    public function getTargetId()
    {
        return $this->targetId;
    }

    /**
     * Set seen
     *
     * @param boolean $seen
     *
     * @return Notification
     */
    public function setSeen($seen)
    {
        $this->seen = $seen;

        return $this;
    }

    /**
     * Get seen
     *
     * @return bool
     */
    public function getSeen()
    {
        return $this->seen;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Notification
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return mixed
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * @param mixed $about
     */
    public function setAbout($about)
    {
        $this->about = $about;
    }

    public function isValid() : bool {

        if($this->fullName == null || $this->phoneNumber == null || $this->message == null)
            return false;
        return true;
    }

    public function getDateFormat() :string {
        return date_format ( $this->date, "d/m/Y г. в  h:i ч." );
    }

    public function createSummary(){
        $summary = "";
        if(strlen($this->message) < 50)
            $summary = $this->message;
        else
            $summary = substr($this->message, 0, 50) . "...";
        return $summary;
    }

    public function manageUser(User $user){
        $this->setPhoneNumber($user->getPhoneNumber());
        $this->setEmail($user->getEmail());
        $this->setFullName($user->getFullName());
        $this->setUsernameId($user->getId());
    }


}

