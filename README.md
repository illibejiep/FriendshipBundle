# Friendship bundle

It provide simple relation functionality for symfony2 users.

## Installation

```
    composer require illibejiep/friendship-bundle dev-master
```

Then add in AppKernel.php

```
//...
    $bundles[] = new \FriendshipBundle\FriendshipBundle();

    return $bundles;
//...
```

Done.

## Usage

In twig template:

```
//...
{% include 'FriendshipBundle:Widget:button.html.twig' with {fromUser: someUser, toUser: anotherUser} %}
//...
```

It will show friendship button for someUser to manage friendship with anoterUser.