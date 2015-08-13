# Friendship bundle

It provides simple relation functionality for symfony2 users.

[![Latest Stable Version](https://poser.pugx.org/illibejiep/friendship-bundle/v/stable)](https://packagist.org/packages/illibejiep/friendship-bundle) [![Total Downloads](https://poser.pugx.org/illibejiep/friendship-bundle/downloads)](https://packagist.org/packages/illibejiep/friendship-bundle) [![Latest Unstable Version](https://poser.pugx.org/illibejiep/friendship-bundle/v/unstable)](https://packagist.org/packages/illibejiep/friendship-bundle) [![License](https://poser.pugx.org/illibejiep/friendship-bundle/license)](https://packagist.org/packages/illibejiep/friendship-bundle)

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

It will show friendship button for someUser to manage friendship with anotherUser.
