<?php
namespace NameISP\API3;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use NameISP\API3\Exception\AuthFailedException;
use NameISP\API3\Exception\InvalidApiResponseException;
use NameISP\API3\Exception\NetworkException;

class AuthManager
{
    use ParseRequestTrait;

    /** @var ClientInterface */
    protected $client;
    /** @var string */
    protected $apiKey;
    /** @var string|null */
    protected $token = null;

    public function __construct($apiKey, ClientInterface $client)
    {
        $this->apiKey = $apiKey;
        $this->client = $client;
    }

    /**
     * @throws AuthFailedException
     * @throws NetworkException
     */
    public function auth()
    {
        $url = 'authenticate/login/'.$this->apiKey;

        try {
            $response = $this->client->request('get', $url);
        } catch (GuzzleException $e) {
            throw new NetworkException($e->getMessage(), $e->getCode(), $e);
        }

        try {
            $result = $this->parseRequest($response);
        } catch (InvalidApiResponseException $e) {
            throw new AuthFailedException($e->getMessage());
        }

        if (!isset($result['parameters']['token'])) {
            throw new AuthFailedException('Can\'t find a token in the auth response');
        }

        // TODO: save externally
        $this->token = $result['parameters']['token'];
    }

    public function getToken()
    {
        return $this->token;
    }

    public function isAuthorized()
    {
        return $this->token !== null;
    }
}
