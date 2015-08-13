<?php

namespace FriendshipBundle\Event;


use Symfony\Component\EventDispatcher\Event;
use FriendshipBundle\Entity\FriendshipRequest;

class FriendshipEvent extends Event {

    /** @var FriendshipRequest */
    protected $request;

    function __construct(FriendshipRequest $request)
    {
        $this->request = $request;
    }

    /**
     * @return \FriendshipBundle\Entity\FriendshipRequest
     */
    public function getRequest()
    {
        return $this->request;
    }
}
