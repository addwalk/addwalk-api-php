<?php

namespace addwalk\Service;

use \League\OAuth2\Client\Token\AccessToken as AccessToken;

class OAuth2Provider extends \League\OAuth2\Client\Provider\IdentityProvider
{

  public $token = false;
  public $baseUrl = 'http://api.addwalk.com/';

  public function __construct($options)
  {
    parent::__construct($options);
  }

  public function urlAuthorize()
  {
    return $this->baseUrl.'oauth/token/';
  }

  public function urlAccessToken()
  {
    return $this->baseUrl.'oauth/token/';
  }

  public function urlUserDetails(AccessToken $token) {}

  public function userDetails($response, AccessToken $token) {}

  public function userUid($response, AccessToken $token) {}

  public function callUrl($url, $requestParams = array()) {
    $client = $this->getHttpClient();
    $client->setBaseUrl($this->baseUrl.$url . '?' . $this->httpBuildQuery($requestParams, '', '&'));

    // important: that sends the token to every request!!
    $auth = 'Bearer '.$this->token->accessToken;

    $request = $client->get(null, array(
      'Authorization' => $auth,
      'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8'))->send(array());

    $response = $request->getBody();
    return json_decode($response, true);
  }

  public function getAccessToken($grant = 'authorization_code', $params = array())
  {
    $requestParams = array(
      'client_id'     => $this->clientId,
      'client_secret' => $this->clientSecret,
      'grant_type'    => "password",
      'username'      => $params["username"],
      'password'      => $params["password"]
    );

    try {
      $client = $this->getHttpClient();
      $client->setBaseUrl($this->urlAccessToken());
      $request = $client->post(null, null, $requestParams)->send();
      $response = $request->getBody();
    } catch (BadResponseException $e) {
      // @codeCoverageIgnoreStart
      $raw_response = explode("\n", $e->getResponse());
      $response = end($raw_response);
      // @codeCoverageIgnoreEnd
    }

    $result = json_decode($response, true);
    if (isset($result['error']) && ! empty($result['error'])) {
      throw new IDPException($result);
    }
    $pure_token = $result["access_token"];
    $this->token = new AccessToken($result);
    return $this->token;
  }

}
