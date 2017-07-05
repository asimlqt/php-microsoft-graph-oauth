<?php

namespace Asimlqt\GraphOauth;

use League\OAuth2\Client\Provider\GenericProvider;
use Asimlqt\GraphOauth\Storage\StorageInterface;
use Asimlqt\GraphOauth\Session\SessionInterface;
use League\OAuth2\Client\Token\AccessToken;
use Asimlqt\GraphOauth\Storage\ReadException;
use Asimlqt\GraphOauth\AccessTokenNotFoundException;

/**
 * Provider
 *
 * @author Asim Liaquat <asimlqt22@gmail.com>
 */
class Provider
{
    /**
     *
     * @var string
     */
    private $userIdentifier;

    /**
     *
     * @var string
     */
    private $clientId;

    /**
     *
     * @var string
     */
    private $clientSecret;

    /**
     *
     * @var string
     */
    private $redirectUri;

    /**
     *
     * @var string
     */
    private $urlAuthorize = "https://login.microsoftonline.com/common/oauth2/v2.0/authorize";

    /**
     *
     * @var string
     */
    private $urlAccessToken = "https://login.microsoftonline.com/common/oauth2/v2.0/token";

    /**
     *
     * @var string
     */
    private $scopes;

    /**
     *
     * @var StorageInterface
     */
    private $storage;

    /**
     *
     * @var SessionInterface
     */
    private $session;

    /**
     *
     * @param StorageInterface $storage
     * @param SessionInterface $session
     * @param string           $userIdentifier
     * @param string           $clientId
     * @param string           $clientSecret
     * @param string           $redirectUri
     * @param string           $scopes
     */
    public function __construct(
        StorageInterface $storage,
        SessionInterface $session,
        $userIdentifier,
        $clientId,
        $clientSecret,
        $redirectUri,
        $scopes
    ) {
        $this->storage = $storage;
        $this->session = $session;
        $this->userIdentifier = $userIdentifier;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
        $this->scopes = $scopes;
    }

    /**
     *
     * @return GenericProvider
     */
    private function getProvider()
    {
        $provider = new GenericProvider([
            "clientId"                => $this->clientId,
            "clientSecret"            => $this->clientSecret,
            "redirectUri"             => $this->redirectUri,
            "urlAuthorize"            => $this->urlAuthorize,
            "urlAccessToken"          => $this->urlAccessToken,
            "urlResourceOwnerDetails" => "",
            "scopes"                  => $this->scopes
        ]);

        return $provider;
    }

    /**
     * Redirects the user to the Microsoft OAuth website
     *
     * @return void
     */
    public function authorize()
    {
        $provider = $this->getProvider();

        $authorizationUrl = $provider->getAuthorizationUrl();

        $this->session->set("state", $provider->getState());

        header('Location: ' . $authorizationUrl);
        exit;
    }

    /**
     *
     * @param string $code
     * @param string $state
     *
     * @return AccessToken
     *
     * @throws StateMismatchException
     */
    public function getAccessTokenFromCode($code, $state)
    {
        if (empty($state) || $state !== $this->session->get("state")) {
            $this->session->remove("state");
            throw new StateMismatchException();
        }

        $provider = $this->getProvider();

        $accessToken = $provider->getAccessToken('authorization_code', [
            "code" => $code
        ]);

        $this->storage->write($accessToken, $this->userIdentifier);

        return $accessToken;
    }

    /**
     *
     * @return AccessToken
     *
     * @throws AccessTokenNotFoundException
     */
    public function getAccessToken()
    {
        try {
            $accesstoken = $this->storage->read($this->userIdentifier);
        } catch (ReadException $e) {
            throw new AccessTokenNotFoundException();
        }

        if ($accesstoken->hasExpired()) {
            $newAccessToken = $this->getProvider()->getAccessToken('refresh_token', [
                'refresh_token' => $accesstoken->getRefreshToken()
            ]);
            $this->storage->write($newAccessToken, $this->userIdentifier);
            return $newAccessToken;
        }

        return $accesstoken;
    }

    /**
     *
     * @return bool
     */
    public function hasAuthorized()
    {
        return $this->storage->fileExists($this->userIdentifier);
    }

    /**
     *
     * @return string
     */
    public function getUserIdentifier()
    {
        return $this->userIdentifier;
    }

    /**
     *
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     *
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     *
     * @return string
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    /**
     *
     * @return string
     */
    public function getUrlAuthorize()
    {
        return $this->urlAuthorize;
    }

    /**
     *
     * @return string
     */
    public function getUrlAccessToken()
    {
        return $this->urlAccessToken;
    }

    /**
     *
     * @param string $userIdentifier
     */
    public function setUserIdentifier($userIdentifier)
    {
        $this->userIdentifier = $userIdentifier;
    }

    /**
     *
     * @param string $clientId
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     *
     * @param string $clientSecret
     *
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     *
     * @param string $redirectUri
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;
    }

    /**
     *
     * @param string $urlAuthorize
     */
    public function setUrlAuthorize($urlAuthorize)
    {
        $this->urlAuthorize = $urlAuthorize;
    }

    /**
     *
     * @param string $urlAccessToken
     */
    public function setUrlAccessToken($urlAccessToken)
    {
        $this->urlAccessToken = $urlAccessToken;
    }

    /**
     *
     * @return string
     */
    public function getScopes()
    {
        return $this->scopes;
    }

    /**
     *
     * @return StorageInterface
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     *
     * @return SessionInterface
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     *
     * @param string $scopes
     */
    public function setScopes($scopes)
    {
        $this->scopes = $scopes;
    }

    /**
     *
     * @param StorageInterface $storage
     */
    public function setStorage(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     *
     * @param SessionInterface $session
     */
    public function setSession(SessionInterface $session)
    {
        $this->session = $session;
    }

}
