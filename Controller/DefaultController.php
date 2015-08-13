<?php

namespace FriendshipBundle\Controller;

use FriendshipBundle\Entity\FriendshipRequest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;

class DefaultController extends Controller
{
    /**
     * @param $toUserId
     * @return JsonResponse
     */
    public function requestAction($toUsername)
    {
        /** @var UserInterface $user */
        $user = $this->getUser();

        $jsonResponse = new JsonResponse();

        if (!$user) {
            return $jsonResponse->setData(array('error' => 'unauthorized user'));
        }

        $dispatcher = $this->get('event_dispatcher');
        $manager = $this->get('friendship.manager');
        try {

            $request = $manager->prepareRequest($user->getUsername(), $toUsername);
            $dispatcher->dispatch(FriendshipEvents::REQUEST_BEFORE, new FriendshipEvent($request));

            $manager->processRequest($request);
            $dispatcher->dispatch(FriendshipEvents::REQUEST_AFTER, new FriendshipEvent($request));

        } catch (\Exception $e) {
            return $jsonResponse->setData(array('error' => $e->getMessage()));
        }

        $jsonResponse->setData(array(
            'result' => 'success',
            'request' => $request
        ));

        return $jsonResponse;

    }

    /**
     * @param $request FriendshipRequest
     * @return JsonResponse
     * @ParamConverter(name="request", Class="FriendshipBundle:FriendshipRequest")
     */
    public function acceptAction(FriendshipRequest $request = null)
    {
        $jsonResponse = new JsonResponse();

        /** @var UserInterface $user */
        $user = $this->getUser();

        if (!$request || $user->getUsername() != $request->getToUsername()) {
            return $jsonResponse->setData(array('error' => 'wrong request id'));
        }

        $dispatcher = $this->get('event_dispatcher');
        $manager = $this->get('friendship.manager');

        try {

            $dispatcher->dispatch(FriendshipEvents::ACCEPT_BEFORE, new FriendshipEvent($request));
            $request = $manager->acceptRequest($request);
            $dispatcher->dispatch(FriendshipEvents::ACCEPT_AFTER, new FriendshipEvent($request));

        } catch (\Exception $e) {
            return $jsonResponse->setData(array('error' => $e->getMessage()));
        }

        $jsonResponse->setData(array(
            'result' => 'success',
            'request' => $request
        ));

        return $jsonResponse;
    }

    /**
     * @param $request FriendshipRequest
     * @return JsonResponse
     * @ParamConverter(name="request", Class="FriendshipBundle:FriendshipRequest")
     */
    public function rejectAction(FriendshipRequest $request = null)
    {
        $jsonResponse = new JsonResponse();

        /** @var UserInterface $user */
        $user = $this->getUser();

        if (!$request || $user->getUsername() != $request->getToUsername()) {
            return $jsonResponse->setData(array('error' => 'wrong request id'));
        }

        $dispatcher = $this->get('event_dispatcher');
        $manager = $this->get('friendship.manager');

        try {

            $dispatcher->dispatch(FriendshipEvents::REJECT_BEFORE, new FriendshipEvent($request));
            $request = $manager->rejectRequest($request);
            $dispatcher->dispatch(FriendshipEvents::REJECT_AFTER, new FriendshipEvent($request));

        } catch (\Exception $e) {
            return $jsonResponse->setData(array('error' => $e->getMessage()));
        }

        $jsonResponse->setData(array(
            'result' => 'success',
            'request' => $request
        ));

        return $jsonResponse;
    }
}
