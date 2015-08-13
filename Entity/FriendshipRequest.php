<?php

namespace FriendshipBundle\Entity;

class FriendshipRequest implements \JsonSerializable
{
    const STATUS_WAITING = 0;
    const STATUS_ACCEPTED = 1;
    const STATUS_REJECTED = 2;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $status = self::STATUS_WAITING;

    /**
     * @var string
     */
    private $fromUsername;

    /**
     * @var string
     */
    private $toUsername;


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
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $fromUsername
     */
    public function setFromUsername($fromUsername)
    {
        $this->fromUsername = $fromUsername;
    }

    /**
     * @return string
     */
    public function getFromUsername()
    {
        return $this->fromUsername;
    }

    /**
     * @param string $toUsername
     */
    public function setToUsername($toUsername)
    {
        $this->toUsername = $toUsername;
    }

    /**
     * @return string
     */
    public function getToUsername()
    {
        return $this->toUsername;
    }

    public function isAccepted()
    {
        return $this->status === self::STATUS_ACCEPTED;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isWaiting()
    {
        return $this->status === self::STATUS_WAITING;
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
            'fromUsername' => $this->fromUsername,
            'toUser' => $this->toUsername,
            'status' => $this->status,
            'isAccepted' => $this->isAccepted(),
            'isRejected' => $this->isRejected(),
            'isWaiting' => $this->isWaiting(),
        );
    }
}
