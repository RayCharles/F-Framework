<?php
class F_Logger_File extends F_Logger {

    /**
     * @var FFileLogger
     */
    private static $instance = NULL;

    /**
     *
     * @return FFileLogger
     */
    public static function getInstance ()
    {
        if ( NULL === self::$instance ) {
            self::$instance = new self ();
            $config = FConfiguration::getInstance ();
            self::$instance->setDateFormat($config->log_default_date_format);
        }
        return self::$instance;
    }

    private function __clone ()
    {

    }

    public static function info ( $msg, $method )
    {
        self::write ( F_Logger::L_INFO, $msg, $method );
    }

    public static function debug ( $msg, $method )
    {
        self::write ( F_Logger::L_DEBUG, $msg, $method );
    }

    public static function error ( $msg, $method )
    {
        self::write ( F_Logger::L_ERROR, $msg, $method );
    }

    public static function write ( $level, $msg, $method )
    {
        $log = $level . ": " . date ( F_Logger_File::getInstance ()->getDateFormat () ) . " {$method} => {$msg}";

        $file = fopen ( ROOT . DS . FRAMEWORK . DS . 'tmp' . DS . 'logs' . DS . date ( 'Y-m-d' ) . '-log.txt', "a+" );
        fwrite ( $file, $log );
        fwrite ( $file, "\r\n" );
        fclose ( $file );
        return TRUE;
    }
}