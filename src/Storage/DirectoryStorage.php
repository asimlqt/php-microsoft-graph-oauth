<?php

namespace Asimlqt\GraphOauth\Storage;

use League\OAuth2\Client\Token\AccessToken;

/**
 * DirectoryStorage
 *
 * @author Asim Liaquat <asimlqt22@gmail.com>
 */
class DirectoryStorage implements StorageInterface
{
    /**
     *
     * @var string
     */
    private $dir;

    /**
     *
     * @param string $dir
     */
    public function __construct($dir)
    {
        $this->dir = $dir;
    }

    /**
     * {@inheritdoc}
     */
    public function read($userIdentifier)
    {
        $filename = $this->getFilename($userIdentifier);

        if (!file_exists($filename)) {
            throw new ReadException("File '. $filename .' does not exist");
        }

        $options = json_decode(file_get_contents($filename), true);
        return new AccessToken($options);
    }

    /**
     * {@inheritdoc}
     */
    public function write(AccessToken $accessToken, $userIdentifier)
    {
        $success = file_put_contents(
            $this->getFilename($userIdentifier),
            json_encode($accessToken)
        );

        if ($success === false) {
            throw new WriteException("Error writing file");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function fileExists($userIdentifier)
    {
        return file_exists($this->getFilename($userIdentifier));
    }

    /**
     *
     * @param string $userIdentifier
     *
     * @return string
     */
    private function getFilename($userIdentifier)
    {
        return sprintf(
            "%s%s-access_token",
            $this->getDir(),
            substr(md5($userIdentifier), 0, 7)
        );
    }

    /**
     *
     * @return string
     */
    private function getDir()
    {
        $dir = substr($this->dir, -1) === "/" ? $this->dir : $this->dir . "/";

        if (!is_dir($dir)) {
            mkdir($dir, 0777);
        }

        return $dir;
    }

}
