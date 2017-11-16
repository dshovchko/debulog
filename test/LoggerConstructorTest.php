<?php

/**
 *      This is a simple Logger implementation that other Loggers can inherit from.
 *
 *      @package php_debulog
 *      @version 1.0
 *      @author Dmitry Shovchko <d.shovchko@gmail.com>
 *
 */

namespace DebulogTest;

use Debulog;

class StubLogger1 extends Debulog\Logger
{
    public function sync(){}
}

class LoggerConstructorTest extends TestCase
{
    public function test_constructor()
    {
        $stub = new StubLogger1('/var/log', 'myprogram', true);
        $message = PHP_EOL . 'start of debugging at ' . strftime('%d.%m.%Y %H:%M:%S ') . PHP_EOL;

        $this->assertEquals(
            '/var/log',
            $this->invokeProperty($stub, 'dir')->getValue($stub)
        );
        $this->assertEquals(
            'myprogram',
            $this->invokeProperty($stub, 'prefix')->getValue($stub)
        );
        $this->assertEquals(
            true,
            $this->invokeProperty($stub, 'ondebug')->getValue($stub)
        );
        $this->assertEquals(
            array(),
            $this->invokeProperty($stub, '_messages')->getValue($stub)
        );
        $this->assertEquals(
            array(),
            $this->invokeProperty($stub, '_errors')->getValue($stub)
        );
        $this->assertEquals(
            array(
                0 => $message
            ),
            $this->invokeProperty($stub, '_debugs')->getValue($stub)
        );
    }

    public function test_constructor_defaults()
    {
        $stub = new StubLogger1('/var/log');

        $this->assertEquals(
            'my',
            $this->invokeProperty($stub, 'prefix')->getValue($stub)
        );
        $this->assertEquals(
            false,
            $this->invokeProperty($stub, 'ondebug')->getValue($stub)
        );
    }
}
