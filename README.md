# Qutee Service Provider for Silex

[![Build Status](https://travis-ci.org/anorgan/QuteeServiceProvider.png)](https://travis-ci.org/anorgan/QuteeServiceProvider)

[Silex](http://silex.sensiolabs.org/) Service Provider for queue manager and task processor - [QuTee](https://github.com/anorgan/QuTee).

## Instalation

Qutee Service Provider is easily installed via [Composer](http://getcomposer.org) by requiring `"anorgan/qutee-service-provider": "*"`.

```json
{
    "require": {
        "anorgan/qutee-service-provider": "*"
    },
    "minimum-stability": "dev"
}
```

## Usage

Register the Qutee Service Provider in Silex application, provide the configuration via `qutee.config` key.

```php
<?php

// Register and configure the service provider

$app->register(new \Qutee\Silex\QuteeServiceProvider(), array(
    'qutee.config' => array(
        'persistor' => 'Redis',
        'options'   => array(
            'host'  => '127.0.0.1',
            'port'  => 6379
        )
    )
));

// Create tasks
$app['qutee.create_task'](
    'Acme/DeleteFolder',
    array('path' => '/usr'),
    \Qutee\Task::PRIORITY_HIGH
);

// Process tasks
$app['qutee.worker']
    ->setInterval(30)
    ->run();
```

## Links

* [QuTee](https://github.com/anorgan/QuTee)
* [Silex](http://silex.sensiolabs.org/)