<?php

namespace addwalk\Service;

use Guzzle\Service\Client as GuzzleClient;
use Guzzle\Http\Exception\BadResponseException;

class Client
{

  public $api_version = "1";

  public function __construct($auth_provider)
  {
    $this->auth_provider = $auth_provider;
    $this->setHttpClient(new GuzzleClient);
  }

  /**
   * Fetch All Categories
   *
   * @param  Array $options Params should be included
   * @return Array Array of Json-Objects
   */
  public function index(Array $options = [], $body = true)
  {
    $path = $this->auth_provider->baseUrl.$this->api_version.'/'.$this->path;
    return $this->decodeResponse($this->getRequest($path));
  }

  private function getRequest($path)
  {
    $request = $this->getHttpClient()->setBaseUrl($path)->get(null, $this->getHttpClientBasicParams())->send(array());
    $response = $request->getBody();
    return $response;
  }

  private function postRequest($path)
  {
  }

  private function putRequest($path)
  {
  }

  private function destroyRequest($path)
  {
  }

  private function decodeResponse($response)
  {
    return json_decode($response, true);
  }

  private function setHttpClient(GuzzleClient $client)
  {
    $this->httpClient = $client;
    return $this;
  }

  private function getHttpClient()
  {
      $client = clone $this->httpClient;
      return $client;
  }

  private function getHttpClientBasicParams()
  {
    return array(
      'Authorization' => 'Bearer '.$this->auth_provider->token->accessToken,
      'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8'
    );
  }

}
