<?php

// This snippet (and some of the curl code) due to the Facebook SDK.
if (!function_exists('curl_init')) {
  throw new Exception('Proby needs the CURL PHP extension.');
}
if (!function_exists('json_decode')) {
  throw new Exception('Proby needs the JSON PHP extension.');
}


abstract class Proby
{
  public static $apiKey;
  public static $apiBase = 'https://proby.signalhq.com';
  const VERSION = '1.0.0';

  public static function getApiKey()
  {
    return self::$apiKey;
  }

  public static function setApiKey($apiKey)
  {
    self::$apiKey = $apiKey;
  }

  public static function sendStartNotification($taskId)
  {
    return Proby_Notifier::sendNotification($taskId, 'start');
  }

  public static function sendFinishNotification($taskId)
  {
    return Proby_Notifier::sendNotification($taskId, 'finish');
  }
}


// Errors
require(dirname(__FILE__) . '/Proby/Error.php');
require(dirname(__FILE__) . '/Proby/ApiConnectionError.php');
require(dirname(__FILE__) . '/Proby/ApiError.php');
require(dirname(__FILE__) . '/Proby/AuthenticationError.php');
require(dirname(__FILE__) . '/Proby/InvalidRequestError.php');

require(dirname(__FILE__) . '/Proby/ApiRequestor.php');
require(dirname(__FILE__) . '/Proby/Notifier.php');

