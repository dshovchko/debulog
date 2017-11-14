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

class StubLogger2 extends Debulog\Logger
{
    public function __construct($dir, $prefix='my', $debug=false)
    {
        $this->dir = $dir;
        $this->prefix = $prefix;
        $this->ondebug = $debug;

        if ($this->ondebug === TRUE)
        {
            $this->_debugs[] = PHP_EOL . $this->log_debug_event('start');
        }
    }
}

class LoggerTest extends TestCase
{
    public function setUp()
    {
        if ( ! is_dir('temp'))
        {
            mkdir('temp');
        }
    }
    public function tearDown()
    {
        $this->delTree('temp');
    }

    public function delTree($dir)
    {
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file)
        {
            (is_dir("$dir/$file")) ? $this->delTree("$dir/$file") : unlink("$dir/$file");
        }

        return rmdir($dir);
    }

    public function test_add_without_debug()
    {
        $expect_add = array();
        $expect_error = array();
        $expect_debug = array();
        $stub = new StubLogger2('temp/', 'debulog', false);
        $message = 'simply string for add() test';
        $stub->add($message);
        $expect_add[] = strftime('%d.%m.%Y %H:%M:%S ') . $message . PHP_EOL;

        $this->assertEquals(
            $expect_add,
            $this->invokeProperty($stub, '_messages')->getValue($stub)
        );

        $this->assertEquals(
            $expect_error,
            $this->invokeProperty($stub, '_errors')->getValue($stub)
        );

        $this->assertEquals(
            $expect_debug,
            $this->invokeProperty($stub, '_debugs')->getValue($stub)
        );
    }

    public function test_add_with_debug()
    {
        $expect_add = array();
        $expect_error = array();
        $expect_debug = array();
        $stub = new StubLogger2('temp/', 'debulog', true);
        $expect_debug[] = PHP_EOL . 'start of debugging at ' . strftime('%d.%m.%Y %H:%M:%S ') . PHP_EOL;
        $message = 'simply string for add() test';
        $stub->add($message);
        $expect_add[] = strftime('%d.%m.%Y %H:%M:%S ') . $message . PHP_EOL;
        $expect_debug[] = $message . PHP_EOL;

        $this->assertEquals(
            $expect_add,
            $this->invokeProperty($stub, '_messages')->getValue($stub)
        );
        $this->assertEquals(
            $expect_error,
            $this->invokeProperty($stub, '_errors')->getValue($stub)
        );

        $this->assertEquals(
            $expect_debug,
            $this->invokeProperty($stub, '_debugs')->getValue($stub)
        );
    }

    public function test_error_without_debug()
    {
        $expect_add = array();
        $expect_error = array();
        $expect_debug = array();
        $stub = new StubLogger2('temp/', 'debulog', false);
        $message = 'simply string for error() test';
        $stub->error($message);
        $expect_error[] = strftime('%d.%m.%Y %H:%M:%S ') . $message . PHP_EOL;

        $this->assertEquals(
            $expect_add,
            $this->invokeProperty($stub, '_messages')->getValue($stub)
        );

        $this->assertEquals(
            $expect_error,
            $this->invokeProperty($stub, '_errors')->getValue($stub)
        );

        $this->assertEquals(
            $expect_debug,
            $this->invokeProperty($stub, '_debugs')->getValue($stub)
        );
    }

    public function test_error_with_debug()
    {
        $expect_add = array();
        $expect_error = array();
        $expect_debug = array();
        $stub = new StubLogger2('temp/', 'debulog', true);
        $expect_debug[] = PHP_EOL . 'start of debugging at ' . strftime('%d.%m.%Y %H:%M:%S ') . PHP_EOL;
        $message = 'simply string for add() test';
        $stub->error($message);
        $expect_error[] = strftime('%d.%m.%Y %H:%M:%S ') . $message . PHP_EOL;
        $expect_debug[] = 'ERROR: ' . $message . PHP_EOL;

        $this->assertEquals(
            $expect_add,
            $this->invokeProperty($stub, '_messages')->getValue($stub)
        );
        $this->assertEquals(
            $expect_error,
            $this->invokeProperty($stub, '_errors')->getValue($stub)
        );

        $this->assertEquals(
            $expect_debug,
            $this->invokeProperty($stub, '_debugs')->getValue($stub)
        );
    }

    public function test_debug_without_debug()
    {
        $expect_add = array();
        $expect_error = array();
        $expect_debug = array();
        $stub = new StubLogger2('temp/', 'debulog', false);
        $message = 'simply string for debug() test';
        $stub->debug($message);

        $this->assertEquals(
            $expect_add,
            $this->invokeProperty($stub, '_messages')->getValue($stub)
        );

        $this->assertEquals(
            $expect_error,
            $this->invokeProperty($stub, '_errors')->getValue($stub)
        );

        $this->assertEquals(
            $expect_debug,
            $this->invokeProperty($stub, '_debugs')->getValue($stub)
        );
    }

    public function test_debug_with_debug()
    {
        $expect_add = array();
        $expect_error = array();
        $expect_debug = array();
        $stub = new StubLogger2('temp/', 'debulog', true);
        $expect_debug[] = PHP_EOL . 'start of debugging at ' . strftime('%d.%m.%Y %H:%M:%S ') . PHP_EOL;
        $message = 'simply string for add() test';
        $stub->debug($message);
        $expect_debug[] = $message . PHP_EOL;

        $this->assertEquals(
            $expect_add,
            $this->invokeProperty($stub, '_messages')->getValue($stub)
        );
        $this->assertEquals(
            $expect_error,
            $this->invokeProperty($stub, '_errors')->getValue($stub)
        );

        $this->assertEquals(
            $expect_debug,
            $this->invokeProperty($stub, '_debugs')->getValue($stub)
        );
    }

    public function test_shutdown_without_debug()
    {
        $expect_add = array();
        $expect_error = array();
        $expect_debug = array();
        $stub = new StubLogger2('temp/', 'debulog', false);

        $message = 'simply string for add() test';
        $stub->add($message);
        $expect_add[] = strftime('%d.%m.%Y %H:%M:%S ') . $message . PHP_EOL;
        $stub->add($message);
        $expect_add[] = strftime('%d.%m.%Y %H:%M:%S ') . $message . PHP_EOL;

        $message = 'simply string for error() test';
        $stub->error($message);
        $expect_error[] = strftime('%d.%m.%Y %H:%M:%S ') . $message . PHP_EOL;
        $stub->error($message);
        $expect_error[] = strftime('%d.%m.%Y %H:%M:%S ') . $message . PHP_EOL;

        $message = 'simply string for debug() test';
        $stub->debug($message);

        $stub->shutdown();

        $this->assertFileExists('temp/debulog.log');
        $content = file_get_contents('temp/debulog.log');
        $this->assertEquals(
            implode('', $expect_add),
            $content
        );

        $this->assertFileExists('temp/debulog_error.log');
        $content = file_get_contents('temp/debulog_error.log');
        $this->assertEquals(
            implode('', $expect_error),
            $content
        );

        $this->assertFileNotExists('temp/debulog_debug.log');
    }

    public function test_shutdown_with_debug()
    {
        $expect_add = array();
        $expect_error = array();
        $expect_debug = array();
        $stub = new StubLogger2('temp/', 'debulog', true);
        $expect_debug[] = PHP_EOL . 'start of debugging at ' . strftime('%d.%m.%Y %H:%M:%S ') . PHP_EOL;

        $message = 'simply string for add() test';
        $stub->add($message);
        $expect_add[] = strftime('%d.%m.%Y %H:%M:%S ') . $message . PHP_EOL;
        $expect_debug[] = $message . PHP_EOL;
        $stub->add($message);
        $expect_add[] = strftime('%d.%m.%Y %H:%M:%S ') . $message . PHP_EOL;
        $expect_debug[] = $message . PHP_EOL;

        $message = 'simply string for error() test';
        $stub->error($message);
        $expect_error[] = strftime('%d.%m.%Y %H:%M:%S ') . $message . PHP_EOL;
        $expect_debug[] = 'ERROR: ' . $message . PHP_EOL;
        $stub->error($message);
        $expect_error[] = strftime('%d.%m.%Y %H:%M:%S ') . $message . PHP_EOL;
        $expect_debug[] = 'ERROR: ' . $message . PHP_EOL;

        $message = 'simply string for debug() test';
        $stub->debug($message);
        $expect_debug[] = $message . PHP_EOL;

        $stub->shutdown();
        $expect_debug[] = 'end of debugging at ' . strftime('%d.%m.%Y %H:%M:%S ') . PHP_EOL . PHP_EOL;

        $this->assertFileExists('temp/debulog.log');
        $content = file_get_contents('temp/debulog.log');
        $this->assertEquals(
            implode('', $expect_add),
            $content
        );

        $this->assertFileExists('temp/debulog_error.log');
        $content = file_get_contents('temp/debulog_error.log');
        $this->assertEquals(
            implode('', $expect_error),
            $content
        );

        $this->assertFileExists('temp/debulog_debug.log');
        $content = file_get_contents('temp/debulog_debug.log');
        $this->assertEquals(
            implode('', $expect_debug),
            $content
        );
    }

    public function test_sync_without_debug()
    {
        $expect_add = array();
        $expect_error = array();
        $expect_debug = array();
        $stub = new StubLogger2('temp/', 'debulog', false);

        $message = 'simply string for add() test';
        $stub->add($message);
        $expect_add[] = strftime('%d.%m.%Y %H:%M:%S ') . $message . PHP_EOL;
        $stub->add($message);
        $expect_add[] = strftime('%d.%m.%Y %H:%M:%S ') . $message . PHP_EOL;

        $message = 'simply string for error() test';
        $stub->error($message);
        $expect_error[] = strftime('%d.%m.%Y %H:%M:%S ') . $message . PHP_EOL;
        $stub->error($message);
        $expect_error[] = strftime('%d.%m.%Y %H:%M:%S ') . $message . PHP_EOL;

        $message = 'simply string for debug() test';
        $stub->debug($message);

        $stub->sync();

        $this->assertFileExists('temp/debulog.log');
        $content = file_get_contents('temp/debulog.log');
        $this->assertEquals(
            implode('', $expect_add),
            $content
        );

        $this->assertFileExists('temp/debulog_error.log');
        $content = file_get_contents('temp/debulog_error.log');
        $this->assertEquals(
            implode('', $expect_error),
            $content
        );

        $this->assertFileNotExists('temp/debulog_debug.log');

        $this->assertEquals(
            array(),
            $this->invokeProperty($stub, '_messages')->getValue($stub)
        );

        $this->assertEquals(
            array(),
            $this->invokeProperty($stub, '_errors')->getValue($stub)
        );

        $this->assertEquals(
            array(),
            $this->invokeProperty($stub, '_debugs')->getValue($stub)
        );
    }

    public function test_sync_with_debug()
    {
        $expect_add = array();
        $expect_error = array();
        $expect_debug = array();
        $stub = new StubLogger2('temp/', 'debulog', true);
        $expect_debug[] = PHP_EOL . 'start of debugging at ' . strftime('%d.%m.%Y %H:%M:%S ') . PHP_EOL;

        $message = 'simply string for add() test';
        $stub->add($message);
        $expect_add[] = strftime('%d.%m.%Y %H:%M:%S ') . $message . PHP_EOL;
        $expect_debug[] = $message . PHP_EOL;
        $stub->add($message);
        $expect_add[] = strftime('%d.%m.%Y %H:%M:%S ') . $message . PHP_EOL;
        $expect_debug[] = $message . PHP_EOL;

        $message = 'simply string for error() test';
        $stub->error($message);
        $expect_error[] = strftime('%d.%m.%Y %H:%M:%S ') . $message . PHP_EOL;
        $expect_debug[] = 'ERROR: ' . $message . PHP_EOL;
        $stub->error($message);
        $expect_error[] = strftime('%d.%m.%Y %H:%M:%S ') . $message . PHP_EOL;
        $expect_debug[] = 'ERROR: ' . $message . PHP_EOL;

        $message = 'simply string for debug() test';
        $stub->debug($message);
        $expect_debug[] = $message . PHP_EOL;

        $stub->sync();

        $this->assertFileExists('temp/debulog.log');
        $content = file_get_contents('temp/debulog.log');
        $this->assertEquals(
            implode('', $expect_add),
            $content
        );

        $this->assertFileExists('temp/debulog_error.log');
        $content = file_get_contents('temp/debulog_error.log');
        $this->assertEquals(
            implode('', $expect_error),
            $content
        );

        $this->assertFileExists('temp/debulog_debug.log');
        $content = file_get_contents('temp/debulog_debug.log');
        $this->assertEquals(
            implode('', $expect_debug),
            $content
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
            array(),
            $this->invokeProperty($stub, '_debugs')->getValue($stub)
        );
    }

    public function test_write()
    {
        $stub = new StubLogger2('temp/', 'debulog', true);
        $messages = array();
        $messages[] = 'first message';
        $messages[] = 'second message';

        $this->invokeMethod($stub, 'write', array($messages, 'temp/write_test.log'));

        $this->assertFileExists('temp/write_test.log');
        $content = file_get_contents('temp/write_test.log');
        $this->assertEquals(
            implode('', $messages),
            $content
        );
    }

    /**
     *  @expectedException \Exception
     *  @expectedExceptionMessage Logfile ghfdhsfdgsjgshfdsjfgdshfs/write_test.log is not writeable!
     *
     */
    public function test_write_not_open()
    {
        $stub = new StubLogger2('temp/', 'debulog', true);
        $messages = array();
        $messages[] = 'first message';
        $messages[] = 'second message';

        $this->invokeMethod($stub, 'write', array($messages, 'ghfdhsfdgsjgshfdsjfgdshfs/write_test.log'));
    }
}
