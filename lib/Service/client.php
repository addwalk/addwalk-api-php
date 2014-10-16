<?php

namespace addwalk\Service;

use Guzzle\Service\Client as GuzzleClient;
use Guzzle\Http\Exception\BadResponseException;

class Client
{

  public $api_version = "1";
  private $auth_provider = array();

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

  // /**
  //  * Create a new Category
  //  *
  //  * @param  Array $options Params should be included
  //  * @return Array Array of Json-Objects
  //  */
  // public function create(Array $options = [], $body = true)
  // {
  //   $path = $this->auth_provider->baseUrl.$this->api_version.'/'.$this->path;
  //   return $this->decodeResponse($this->getRequest($path));
  // }


  /**
   * Create a new Category
   *
   * @param  Array $options Params should be included
   * @return Array Array of Json-Objects
   */
  public function update(Array $options = [], $body = true)
  {
    $path = $this->auth_provider->baseUrl.$this->api_version.'/'.$this->path;
    return $this->decodeResponse($this->getRequest($path));
  }



  /**
   * Create a new Category
   *
   * @param  Array $options Params should be included
   * @return Array Array of Json-Objects
   */
  public function create($post = ''){
    $path = $this->auth_provider->baseUrl.$this->api_version.'/'.$this->path;
    return $this->decodeResponse($this->postRequest($path, $post));
  }

  /**
   * Show a Category
   *
   * @param  Array $post Params should be included
   * @return Array Array of Json-Objects
   */
  public function show($id){

    if(!is_numeric($id)  || empty($id)){
      return false;
    }

    $path = $this->auth_provider->baseUrl.$this->api_version.'/'.$this->path.'/'.$id;
    return $this->decodeResponse($this->getRequest($path, $post));
  }

  private function getRequest($path)
  {
    $request = $this->getHttpClient()->setBaseUrl($path)->get(null, $this->getHttpClientBasicParams())->send(array());
    $response = $request->getBody();
    return $response;
  }

  private function postRequest($path, $post)
  {
    $json = $this->toJson($post);
    $request = $this->getHttpClient()->setBaseUrl($path)->createRequest('POST', null, $this->getHttpClientBasicParams(), $json)->send(array());
    $response = $request->getBody();
    return $response;
  }

  private function putRequest($path, $put)
  {
    $json = $this->toJson($put);
    $request = $this->getHttpClient()->put($path, $this->getHttpClientBasicParams(), $request);
    $response = $request->getBody();
    return $response;
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
      'Content-Type' => 'application/json;charset=UTF-8',
      'Accept' => 'application/json',
    );
  }

  private function toJson($value){
    return json_encode($value);
  }

}
