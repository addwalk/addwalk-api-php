<?php

namespace addwalk;

require("Service/oauth2_provider.php");

class Product extends Service\Client
{

  public $path = "products/";

}
