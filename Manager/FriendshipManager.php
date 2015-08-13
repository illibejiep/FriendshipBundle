<?php

namespace FriendshipBundle\Manager;

use Doctrine\ORM\EntityManager;
use FriendshipBundle\Entity\FriendshipRequest;
use Symfony\Component\Security\Core\User\UserInterface;

class FriendshipManager
{

    /** @var  EntityManager */
    protected $em;

    function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function createRequest($fromUsername, $toUsername)
    {
        $request = $this->prepareRequest($fromUsername, $toUsername);
        $this->processRequest($request);

        return $request;
    }

    public function prepareRequest($fromUsername, $toUsername)
    {
        if ($fromUsername == $toUsername) {
            throw new \Exception('You can\'t be friend with yourself');
        }

        $repository = $this->em->getRepository('FriendshipBundle:FriendshipRequest');
        $request = $repository->findOneBy(array(
            'fromUsername' => $fromUsername,
            'toUsername' => $toUsername,
        ));

        if ($request)
            throw new \Exception('request already sent');

        $request = new FriendshipRequest();
        $request->setFromUsername($fromUsername);
        $request->setToUsername($toUsername);

        return $request;
    }

    public function processRequest(FriendshipRequest $request)
    {
        $this->em->persist($request);
        $this->em->flush();

        return $request;
    }

    public function acceptRequest(FriendshipRequest $request)
    {
        $request->setStatus(FriendshipRequest::STATUS_ACCEPTED);

        $mirrorRequest = new FriendshipRequest();
        $mirrorRequest->setStatus(FriendshipRequest::STATUS_ACCEPTED);
        $mirrorRequest->setFromUsername($request->getToUsername());
        $mirrorRequest->setToUsername($request->getFromUsername());

        $this->em->persist($request);
        $this->em->persist($mirrorRequest);
        $this->em->flush($request);

        return $request;
    }

    public function rejectRequest(FriendshipRequest $request)
    {
        $request->setStatus(FriendshipRequest::STATUS_REJECTED);

        $repository = $this->em->getRepository('FriendshipBundle:FriendshipRequest');

        $mirrorRequest = $repository->findOneBy(array(
            'fromUsername' => $request->getToUsername(),
            'toUsername' => $request->getFromUsername(),
        ));

        if ($mirrorRequest)
            $this->em->remove($mirrorRequest);

        $this->em->persist($request);
        $this->em->flush($request);

        return $request;
    }

    public function getRequestsToUser(UserInterface $user)
    {
        $repository = $this->em->getRepository('FriendshipBundle:FriendshipRequest');

        $requests = $repository->findBy(array(
            'toUsername' => $user->getUsername(),
        ));

        return $requests;
    }

    public function getRequestsFromUser(UserInterface $user)
    {
        $repository = $this->em->getRepository('FriendshipBundle:FriendshipRequest');

        $requests = $repository->findBy(array(
            'fromUsername' => $user->getUsername(),
        ));

        return $requests;
    }

    public function getRequestFromUserToUser(UserInterface $fromUser, UserInterface $toUser)
    {
        $repository = $this->em->getRepository('FriendshipBundle:FriendshipRequest');

        $request = $repository->findOneBy(array(
            'fromUsername' => $fromUser->getUsername(),
            'toUsername' => $toUser->getUsername(),
        ));

        return $request;
    }

    public function isFriends(UserInterface $fromUser, UserInterface $toUser)
    {
        $repository = $this->em->getRepository('FriendshipBundle:FriendshipRequest');

        $request = $repository->findOneBy(array(
            'fromUsername' => $fromUser->getUsername(),
            'toUsername' => $toUser->getUsername(),
            'status' => FriendshipRequest::STATUS_ACCEPTED
        ));

        if ($request)
            return true;

        return false;
    }
}
