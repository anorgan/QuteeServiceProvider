<?php

namespace Qutee\Silex;

use Qutee\Queue;
use Qutee\Task;
use Qutee\Worker;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Qutee Service Provider
 *
 * @author anorgan
 */
class QuteeServiceProvider implements ServiceProviderInterface
{

    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {

        $app['qutee.task'] = function () {
            return new Task();
        };

        $app['qutee.worker'] = function () {
            $app['qutee.queue'];
            return new Worker();
        };

        $app['qutee.queue'] = $app->share(function ($app) {
            $config = isset($app['qutee.config']) ? $app['qutee.config'] : array();
            return Queue::factory($config);
        });

        $app['qutee.create_task'] = $app->protect(function ($name, $data = array(), $priority = Task::PRIORITY_NORMAL, $unique_id = null, $methodName = null) use ($app) {
            $app['qutee.queue'];
            return Task::create($name, $data, $priority, $unique_id, $methodName);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
        // NOOP
    }

}