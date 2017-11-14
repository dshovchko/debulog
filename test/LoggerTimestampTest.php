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

class StubLogger3 extends Debulog\Logger
{
    public function __construct() {}
    public function sync(){}
}

class LoggerTimestampTest extends TestCase
{
    public function test_log_debug_event()
    {
        $stub = new StubLogger3();

        $this->assertEquals(
            'test of debugging at ' . strftime('%d.%m.%Y %H:%M:%S ') . PHP_EOL,
            $this->invokeMethod($stub, 'log_debug_event', array('test'))
        );
    }

    public function test_log_timestamp()
    {
        $stub = new StubLogger3();

        $this->assertEquals(
            strftime('%d.%m.%Y %H:%M:%S '),
            $this->invokeMethod($stub, 'log_timestamp', array())
        );
    }
}
