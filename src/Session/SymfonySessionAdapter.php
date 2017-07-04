<?php

namespace Asimlqt\GraphOauth\Session;

use Symfony\Component\HttpFoundation\Session\SessionInterface as SymfonySessionInterface;

/**
 * SymfonySessionAdapter
 *
 * @author Asim Liaquat <asimlqt22@gmail.com>
 */
class SymfonySessionAdapter implements SessionInterface
{
    /**
     *
     * @var SymfonySessionInterface
     */
    private $session;

    /**
     *
     * @param SymfonySessionInterface $session
     */
    public function __construct(SymfonySessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function set($name, $value)
    {
        $this->session->set($name, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function get($name, $default = null)
    {
        return $this->session->get($name, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function has($name)
    {
        return $this->session->has($name);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($name)
    {
        $this->session->remove($name);
    }

}
