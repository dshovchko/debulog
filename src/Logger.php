<?php

/**
 *      This is a simple Logger implementation that other Loggers can inherit from.
 *
 *      @package php_debulog
 *      @version 1.0
 *      @author Dmitry Shovchko <d.shovchko@gmail.com>
 *
 */

namespace Debulog;

abstract class Logger implements LoggerInterface {

    /**
     *      @var string
     */
    protected $dir;

    /**
     *      @var string
     */
    protected $prefix;

    /**
     *      @var boolean
     */
    protected $ondebug;

    /**
     *      @var array
     */
    protected $_messages = array();

    /**
     *      @var array
     */
    protected $_errors = array();

    /**
     *      @var array
     */
    protected $_debugs = array();

    /**
     *      Logger constructor
     *
     */
    public function __construct($dir, $prefix='my', $debug=false)
    {
        $this->dir = $dir;
        $this->prefix = $prefix;
        $this->ondebug = $debug;

        if ($this->ondebug === TRUE)
        {
            $this->_debugs[] = PHP_EOL . $this->log_debug_event('start');
        }

        register_shutdown_function(array($this, 'shutdown'));
    }

    /**
     *      Add message to logger buffer
     *
     *      @param string $message Message text
     *
     */
    public function add($message)
    {
        $this->_messages[] = $this->log_timestamp() . $message . PHP_EOL;
        $this->debug($message);
    }

    /**
     *      Add error message to logger buffer
     *
     *      @param string $message Message text
     *
     */
    public function error($message)
    {
        $this->_errors[] = $this->log_timestamp() . $message . PHP_EOL;
        $this->debug('ERROR: '.$message);
    }

    /**
     *      Add debug message to logger buffer
     *
     *      @param string $message Message to log
     *
     */
    public function debug($message)
    {
        if ($this->ondebug === TRUE)
        {
            $this->_debugs[] = $message . PHP_EOL;
        }
    }

    /**
     *      Finish and sync all buffers to files
     *
     */
    public function shutdown()
    {
        if ($this->ondebug === TRUE)
        {
            $this->_debugs[] = $this->log_debug_event('end') . PHP_EOL;
        }
        $this->sync();
    }

    /**
     *      Sync all buffers to files
     */
    public function sync()
    {
        $this->sync_single_log($this->_messages, '');
        $this->sync_single_log($this->_debugs, '_debug');
        $this->sync_single_log($this->_errors, '_error');
    }

    /**
     *      Sync specific buffer to file
     *
     *      @param array $buffer
     *      @param string $suffix log filename suffix
     */
    protected function sync_single_log(&$buffer, $suffix)
    {
        if ( ! empty($buffer))
        {
            $this->write($buffer, $this->dir . $this->prefix . $suffix . '.log');
            $buffer = array();
        }
    }

    /**
     *      Get formated string of debug event
     *
     *      @param string $event Name of event
     *      @return string
     */
    protected function log_debug_event($event)
    {
        return $event . ' of debugging at ' . $this->log_timestamp() . PHP_EOL;
    }

    /**
     *      Get formatted timestamp
     *
     *      @return string
     */
    protected function log_timestamp()
    {
        return strftime('%d.%m.%Y %H:%M:%S ');
    }

    /**
     *      Write text to file
     *
     *      @param array $messages
     *      @param string $file
     *
     *      @throws Exception
     *
     */
    protected function write($messages, $file)
    {
        $f = @fopen($file, 'a');
        if ($f === false)
        {
            throw new \Exception("Logfile $file is not writeable!");
        }

        foreach($messages as $msg)
        {
            fwrite($f, $msg);
        }

        fclose($f);
    }
}
