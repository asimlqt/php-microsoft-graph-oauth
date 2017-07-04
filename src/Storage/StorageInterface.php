<?php

namespace Asimlqt\GraphOauth\Storage;

use League\OAuth2\Client\Token\AccessToken;

/**
 * StorageInterface
 *
 * @author Asim Liaquat <asimlqt22@gmail.com>
 */
interface StorageInterface
{
    /**
     *
     * @param string $userIdentifier
     *
     * @return AccessToken
     *
     * @throws ReadException
     */
    public function read($userIdentifier);

    /**
     *
     * @param AccessToken $accessToken
     * @param string      $userIdentifier
     *
     * @return void
     *
     * @throws WriteException
     */
    public function write(AccessToken $accessToken, $userIdentifier);

    /**
     *
     * @param string $userIdentifier
     *
     * @return bool
     */
    public function fileExists($userIdentifier);

}
