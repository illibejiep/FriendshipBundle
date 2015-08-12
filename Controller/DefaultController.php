<?php

namespace FriendshipBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use FriendshipBundle\Entity\FriendshipRequest;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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

        $manager = $this->get('friendship.manager');
        try {
            $request = $manager->createRequest($user->getUsername(), $toUsername);
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

        $manager = $this->get('friendship.manager');

        try {
            $manager->acceptRequest($request);
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

        $manager = $this->get('friendship.manager');

        try {
            $manager->rejectRequest($request);
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
