<?php

/**
 *      Describes a logger instance.
 *
 *      @package php_debulog
 *      @version 1.0
 *      @author Dmitry Shovchko <d.shovchko@gmail.com>
 *
 */

namespace Debulog;

interface LoggerInterface {

    /**
     *      Add message to logger buffer
     *
     *      @param string $message Message text
     *
     */
    public function add($message);

    /**
     *      Add error message to logger buffer
     *
     *      @param string $message Message text
     *
     */
    public function error($message);

    /**
     *      Add debug message to logger buffer
     *
     *      @param string $message Message to log
     *
     */
    public function debug($message);

    /**
     *      Sync all buffers to files
     *
     */
    public function sync();

}
