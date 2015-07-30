<?php

namespace FriendshipBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

class FriendshipRequest implements \JsonSerializable
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $isAccepted;

    /**
     * @var UserInterface
     */
    private $fromUser;

    /**
     * @var UserInterface
     */
    private $toUser;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $isAccepted
     */
    public function setIsAccepted($isAccepted)
    {
        $this->isAccepted = $isAccepted;
    }

    /**
     * @return mixed
     */
    public function getIsAccepted()
    {
        return $this->isAccepted;
    }

    /**
     * @param UserInterface $fromUser
     */
    public function setFromUser($fromUser)
    {
        $this->fromUser = $fromUser;
    }

    /**
     * @return UserInterface
     */
    public function getFromUser()
    {
        return $this->fromUser;
    }

    /**
     * @param UserInterface $toUser
     */
    public function setToUser($toUser)
    {
        $this->toUser = $toUser;
    }

    /**
     * @return UserInterface
     */
    public function getToUser()
    {
        return $this->toUser;
    }

    public function isAccepted()
    {
        return $this->isAccepted === true;
    }

    public function isRejected()
    {
        return $this->isAccepted === false;
    }

    public function isWaiting()
    {
        return $this->isAccepted === null;
    }

    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return array(
            'id' => $this->id,
            'fromUser' => $this->fromUser->getUsername(),
            'toUser' => $this->toUser->getUsername(),
            'isAccepted' => $this->isAccepted,
        );
    }


}
