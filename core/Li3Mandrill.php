<?php

namespace li3_mandrill\core;

require_once "libraries/Mandrill.php";

use Exception;
use lithium\core\Environment;

use Mandrill;

/**
 * Wrapper class to allow LI3 communication with Mandrill
 */
class Li3Mandrill extends \lithium\core\StaticObject {

    protected static $mandrill = null;
    protected static $pid = null;
    protected static $config = array(
        'apikey' => null,
    );

    /**
     * Return an instance of the Mandrill class.
     *
     * @return Mandrill Instance.
     */
    public static function getInstance()
    {
        // Detect when the PID of the current process has changed (from a fork, etc)
        // and force a reconnect to redis.
        $pid = getmypid();
        if ( self::$pid !== $pid ) {
            self::$mandrill = null;
            self::$pid   = $pid;
        }

        if( !is_null( self::$mandrill ) ) {
            return self::$mandrill;
        }

        foreach( array_keys( self::$config ) as $param ) {
            if( Environment::get( 'mandrill.' . $param ) ) {
                self::$config[$param] = Environment::get( 'mandrill.' . $param );
            }
        }

        if( !( self::$config['apikey'] ) )
        {
            throw new Exception( 'missing Mandrill Configuration', 500 );
        }

        try {
            self::$mandrill = new Mandrill( self::$config[ 'apikey' ] );
        } catch( Exception $e ) {
            return null;
        }

        return self::$mandrill;
    }

    /**
     * runs requested method (with arguments) on a ironmq instance
     *
     * @param string $name The name of the method called.
     * @param array $args Array of supplied arguments to the method.
     * @return mixed Return value from IronMQ::call() based on the command.
     */
    public static function run( $name, $args ) {
        $mandrill = static::getInstance();

        try {
            $method = array_shift( $args );
            return call_user_func_array( array( $mandrill->$name, $method ), $args );
        } catch( Exception $e ) {
            return false;
        }
    }


    /**
     * Does proxying the method calls
     * @param string $method
     * @param mixed $arguments
     */
    public static function __callStatic( $method, $arguments ) {
        return static::run( $method,$arguments );
    }

}

?>