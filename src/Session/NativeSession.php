<?php

namespace Asimlqt\GraphOauth\Session;

/**
 * NativeSession
 *
 * @author Asim Liaquat <asimlqt22@gmail.com>
 */
class NativeSession implements SessionInterface
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function get($name, $default = null)
    {
        if ($this->has($name)) {
            return $_SESSION[$name];
        }

        return $default;
    }

    /**
     * {@inheritdoc}
     */
    public function has($name)
    {
        return array_key_exists($name, $_SESSION);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($name)
    {
        if ($this->has($name)) {
            unset($_SESSION[$name]);
        }
    }

}
