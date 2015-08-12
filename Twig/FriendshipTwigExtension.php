<?php

namespace FriendshipBundle\Twig;

use FriendshipBundle\Manager\FriendshipManager;

class FriendshipTwigExtension extends \Twig_Extension
{
    /**
     * @var FriendshipManager
     */
    protected $friendshipManager;

    function __construct(FriendshipManager $friendshipManager)
    {
        $this->friendshipManager = $friendshipManager;
    }

    public function getGlobals()
    {
        return array(
            'friendship_manager' => $this->friendshipManager,
        );
    }

    public function getName()
    {
        return 'friendship_extension';
    }
}
