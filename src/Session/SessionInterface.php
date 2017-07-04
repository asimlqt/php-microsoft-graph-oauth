<?php

namespace Asimlqt\GraphOauth\Session;

/**
 * SessionInterface
 *
 * @author Asim Liaquat <asimlqt22@gmail.com>
 */
interface SessionInterface
{
    /**
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return void
     */
    public function set($name, $value);

    /**
     *
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($name, $default = null);

    /**
     *
     * @param string $name
     *
     * @return bool
     */
    public function has($name);

    /**
     *
     * @param string $name
     *
     * @return void
     */
    public function remove($name);
}
