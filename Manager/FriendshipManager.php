<?php

namespace FriendshipBundle\Manager;

use Doctrine\ORM\EntityManager;
use FriendshipBundle\Entity\FriendshipRequest;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class FriendshipManager {

    /** @var  EntityManager */
    protected $em;

    /** @var UserProviderInterface */
    protected $userProvider;

    function __construct(Container $container,  $userProvider)
    {
        $this->em = $container->get('doctrine.orm.entity_manager');
        $this->userProvider = $container->get($userProvider);
    }


    public function createRequest($fromUsername, $toUsername)
    {
        $fromUser = $this->userProvider->loadUserByUsername($fromUsername);
        $toUser = $this->userProvider->loadUserByUsername($toUsername);

        if ($toUser->getUsername() == $fromUser->getUsername()) {
            throw new \Exception('You can\'t friend with yourself');
        }

        $request = new FriendshipRequest();
        $request->setFromUser($fromUser);
        $request->setToUser($toUser);

        $this->em->persist($request);

        try {
            $this->em->flush();
        } catch(UniqueConstraintViolationException $e) {
            throw new \Exception('request already sent');
        }

        return $request;
    }

    public function acceptRequest(FriendshipRequest $request)
    {
        $request->setIsAccepted(true);

        $mirrorRequest = new FriendshipRequest();
        $mirrorRequest->setIsAccepted(true);
        $mirrorRequest->setFromUser($request->getToUser());
        $mirrorRequest->setToUser($request->getFromUser());

        $this->em->persist($request);
        $this->em->persist($mirrorRequest);
        $this->em->flush($request);

        return $this;
    }

    public function rejectRequest(FriendshipRequest $request)
    {
        $request->setIsAccepted(false);

        $repository = $this->em->getRepository('FriendshipBundle:FriendshipRequest');

        $mirrorRequest = $repository->findOneBy(array(
            'fromUser' => $request->getToUser(),
            'toUser' => $request->getFromUser(),
        ));

        if ($mirrorRequest)
            $this->em->remove($mirrorRequest);

        $this->em->persist($request);
        $this->em->flush($request);

        return $this;
    }

    public function getRequestsToUser(UserInterface $user)
    {
        $repository = $this->em->getRepository('FriendshipBundle:FriendshipRequest');

        $requests = $repository->findBy(array(
            'toUser' => $user,
        ));

        return $requests;
    }

    public function getRequestsFromUser(UserInterface $user)
    {
        $repository = $this->em->getRepository('FriendshipBundle:FriendshipRequest');

        $requests = $repository->findBy(array(
            'fromUser' => $user,
        ));

        return $requests;
    }

    public function getRequestFromUserToUser(UserInterface $fromUser, UserInterface $toUser)
    {
        $repository = $this->em->getRepository('FriendshipBundle:FriendshipRequest');

        $request = $repository->findOneBy(array(
            'fromUser' => $fromUser,
            'toUser' => $toUser,
        ));

        return $request;
    }

    public function isFriends(UserInterface $fromUser, UserInterface $toUser)
    {
        $repository = $this->em->getRepository('FriendshipBundle:FriendshipRequest');

        $request = $repository->findOneBy(array(
            'fromUser' => $fromUser,
            'toUser' => $toUser,
        ));

        if ($request && $request->isAccepted())
            return true;

        $request = $repository->findOneBy(array(
            'fromUser' => $toUser,
            'toUser' => $fromUser,
        ));

        if ($request && $request->isAccepted())
            return true;

        return false;
    }
}