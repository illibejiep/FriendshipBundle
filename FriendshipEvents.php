<?php

namespace FriendshipBundle;


class FriendshipEvents {

    /**
     * runs after request creation and before request processing
     */
    const REQUEST_BEFORE = 'friendship.request.before';
    /**
     * runs after request request processing and before json serialization
     */
    const REQUEST_AFTER = 'friendship.request.after';

    /**
     * runs before request acception
     */
    const ACCEPT_BEFORE = 'friendship.accept.before';
    /**
     * runs after request acception and before json serialization
     */
    const ACCEPT_AFTER = 'friendship.accept.after';

    /**
     * runs before request rejection
     */
    const REJECT_BEFORE = 'friendship.reject.before';
    /**
     * runs after request rejection and before json serialization
     */
    const REJECT_AFTER = 'friendship.reject.after';
}
