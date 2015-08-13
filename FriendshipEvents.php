<?php

namespace FriendshipBundle;


class FriendshipEvents {

    const REQUEST_BEFORE = 'friendship.request.before';
    const REQUEST_AFTER = 'friendship.request.after';

    const ACCEPT_BEFORE = 'friendship.accept.before';
    const ACCEPT_AFTER = 'friendship.accept.after';

    const REJECT_BEFORE = 'friendship.reject.before';
    const REJECT_AFTER = 'friendship.reject.after';
}
