<?php

/**
 *      Mock class for logging
 *
 *      @package php_debulog
 *      @version 1.0
 *      @author Dmitry Shovchko <d.shovchko@gmail.com>
 *
 */

namespace DebulogTest;

use Debulog\MockLogger;

class MockLoggerTest extends TestCase
{
    public function test_add()
    {
        $m = new MockLogger();
        $m->add('tset message');
        $m->add('test message');

        $this->assertEquals(
            array('tset message', 'test message'),
            $this->invokeProperty($m, '_add')->getValue($m)
        );
    }

    public function test_show_log()
    {
        $m = new MockLogger();
        $m->add('tset message');
        $m->add('test message');

        $this->assertEquals(
            array('tset message', 'test message'),
            $m->show_log()
        );
    }

    public function test_error()
    {
        $m = new MockLogger();
        $m->error('tset message');
        $m->error('test message');

        $this->assertEquals(
            array('tset message', 'test message'),
            $this->invokeProperty($m, '_error')->getValue($m)
        );
    }

    public function test_show_error_log()
    {
        $m = new MockLogger();
        $m->error('tset message');
        $m->error('test message');

        $this->assertEquals(
            array('tset message', 'test message'),
            $m->show_error_log()
        );
    }

    public function test_debug()
    {
        $m = new MockLogger();
        $m->debug('tset message');
        $m->debug('test message');

        $this->assertEquals(
            array('tset message', 'test message'),
            $this->invokeProperty($m, '_debug')->getValue($m)
        );
    }

    public function test_show_debug_log()
    {
        $m = new MockLogger();
        $m->debug('tset message');
        $m->debug('test message');

        $this->assertEquals(
            array('tset message', 'test message'),
            $m->show_debug_log()
        );
    }

    public function test_show_sync()
    {
        $m = new MockLogger();
        $m->add('tset message');
        $m->add('test message');
        $m->error('tset message');
        $m->error('test message');
        $m->debug('tset message');
        $m->debug('test message');

        $this->assertEquals(
            array('tset message', 'test message'),
            $m->show_log()
        );
        $this->assertEquals(
            array('tset message', 'test message'),
            $m->show_error_log()
        );
        $this->assertEquals(
            array('tset message', 'test message'),
            $m->show_debug_log()
        );

        $m->sync();

        $this->assertEquals(
            array(),
            $m->show_log()
        );
        $this->assertEquals(
            array(),
            $m->show_error_log()
        );
        $this->assertEquals(
            array(),
            $m->show_debug_log()
        );
    }

}
