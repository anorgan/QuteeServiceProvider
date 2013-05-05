<?php

namespace Qutee\Silex;

use PHPUnit_Framework_TestCase;
use Silex\Application;

/**
 * QuteeServiceProviderTest
 *
 * @author anorgan
 */
class QuteeServiceProviderTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     * @var Application
     */
    protected $app;

    public function setUp()
    {
        $this->app = new Application();

        $this->app->register(new QuteeServiceProvider, array(
            'qutee.config' => array(
                'persistor' => 'Memory'
            )
        ));
    }

    /**
     * @covers QuteeServiceProvider::register
     */
    public function testCanRegisterProviderWithoutConfiguration()
    {
        $app = new Application();
        $app->register(new QuteeServiceProvider);

        $this->assertInstanceOf('\Qutee\Persistor\Memory', $app['qutee.queue']->getPersistor());
    }

    /**
     * @covers QuteeServiceProvider::register
     */
    public function testCanRegisterProviderWithConfiguration()
    {
        $app = new Application();
        $app->register(new QuteeServiceProvider, array(
            'qutee.config' => array(
                'persistor' => 'Redis',
                'options'   => array(
                    'host'  => '127.0.0.1',
                    'port'  => 6379
                )
            )
        ));

        $this->assertInstanceOf('\Qutee\Persistor\Redis', $app['qutee.queue']->getPersistor());
    }

    /**
     * @covers QuteeServiceProvider::register
     */
    public function testCanGetTask()
    {
        $this->assertInstanceOf('\Qutee\Task', $this->app['qutee.task']);
    }

    /**
     * @covers QuteeServiceProvider::register
     */
    public function testCanGetWorker()
    {
        $this->assertInstanceOf('\Qutee\Worker', $this->app['qutee.worker']);
    }

    /**
     * @covers QuteeServiceProvider::register
     */
    public function testCanGetQueue()
    {
        $this->assertInstanceOf('\Qutee\Queue', $this->app['qutee.queue']);
    }

    /**
     * @covers QuteeServiceProvider::register
     */
    public function testCanCreateTask()
    {
        $createdTask = $this->app['qutee.create_task']('test');

        $this->assertNotEmpty($this->app['qutee.queue']->getTasks());
        $this->assertInstanceOf('\Qutee\Task', $createdTask);
        $this->assertEquals('test', $createdTask->getName());
    }
}