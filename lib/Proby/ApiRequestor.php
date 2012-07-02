<?php

class Proby_ApiRequestor
{
  public static function apiUrl($url='')
  {
    $apiBase = Proby::$apiBase;
    return "$apiBase$url";
  }

  public static function utf8($value)
  {
    if (is_string($value))
      return utf8_encode($value);
    else
      return $value;
  }

  public static function encode($d)
  {
    return http_build_query($d, null, '&');
  }

  public static function fetchErrorMessage($rbody)
  {
    $jsonResponse = json_decode($rbody, true);
    return $jsonResponse["message"];
  }

  public function request($meth, $url, $params=null)
  {
    if (!$params)
      $params = array();
    list($rbody, $rcode, $myApiKey) = $this->_requestRaw($meth, $url, $params);
    $resp = $this->_interpretResponse($rbody, $rcode);
    return array($resp, $myApiKey);
  }

  public function handleApiError($rbody, $rcode, $resp)
  {
    $errorMessage = self::fetchErrorMessage($rbody);

    switch ($rcode) {
    case 400:
    case 404:
      throw new Proby_InvalidRequestError($errorMessage, isset($error['param']) ? $error['param'] : null, $rcode, $rbody, $resp);
    case 401:
      throw new Proby_AuthenticationError($errorMessage, $rcode, $rbody, $resp);
    default:
      throw new Proby_ApiError($errorMessage, $rcode, $rbody, $resp);
    }
  }

  private static function _encodeObjects($d)
  {
    if ($d instanceof Proby_ApiRequestor) {
      return $d->id;
    } else if ($d === true) {
      return 'true';
    } else if ($d === false) {
      return 'false';
    } else if (is_array($d)) {
      $res = array();
      foreach ($d as $k => $v)
        $res[$k] = self::_encodeObjects($v);
      return $res;
    } else {
      return $d;
    }
  }

  private function _requestRaw($meth, $url, $params)
  {
    $myApiKey = Proby::$apiKey;
    if (!$myApiKey)
      throw new Proby_AuthenticationError('No API key provided.  (HINT: set your API key using "Proby::setApiKey(<API-KEY>)".');

    $absUrl = $this->apiUrl($url);
    $params = self::_encodeObjects($params);
    $headers = array('api_key: ' . $myApiKey, 'Content-Type: application/json');
    list($rbody, $rcode) = $this->_curlRequest($meth, $absUrl, $headers, $params);
    return array($rbody, $rcode, $myApiKey);
  }

  private function _interpretResponse($rbody, $rcode)
  {
    try {
      $resp = json_decode($rbody, true);
    } catch (Exception $e) {
      throw new Proby_ApiError("Invalid response body from API: $rbody (HTTP response code was $rcode)", $rcode, $rbody);
    }

    if ($rcode < 200 || $rcode >= 300) {
      $this->handleApiError($rbody, $rcode, $resp);
    }
    return $resp;
  }

  private function _curlRequest($meth, $absUrl, $headers, $params)
  {
    $curl = curl_init();
    $meth = strtolower($meth);
    $opts = array();
    if ($meth == 'get') {
      $opts[CURLOPT_HTTPGET] = 1;
      if (count($params) > 0) {
        $encoded = self::encode($params);
        $absUrl = "$absUrl?$encoded";
      }
    } else if ($meth == 'post') {
      $opts[CURLOPT_POST] = 1;
      $opts[CURLOPT_POSTFIELDS] = json_encode($params);
    } else if ($meth == 'delete')  {
      $opts[CURLOPT_CUSTOMREQUEST] = 'DELETE';
      if (count($params) > 0) {
        $encoded = self::encode($params);
        $absUrl = "$absUrl?$encoded";
      }
    } else {
      throw new Proby_ApiError("Unrecognized method $meth");
    }

    $absUrl = self::utf8($absUrl);
    $opts[CURLOPT_URL] = $absUrl;
    $opts[CURLOPT_RETURNTRANSFER] = true;
    $opts[CURLOPT_CONNECTTIMEOUT] = 30;
    $opts[CURLOPT_TIMEOUT] = 80;
    $opts[CURLOPT_RETURNTRANSFER] = true;
    $opts[CURLOPT_HTTPHEADER] = $headers;
    $opts[CURLOPT_SSL_VERIFYPEER] = false;

    curl_setopt_array($curl, $opts);
    $rbody = curl_exec($curl);

    if ($rbody === false) {
      $errno = curl_errno($curl);
      $message = curl_error($curl);
      curl_close($curl);
      $this->handleCurlError($errno, $message);
    }

    $rcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    return array($rbody, $rcode);
  }

  public function handleCurlError($errno, $message)
  {
    $apiBase = Proby::$apiBase;
    switch ($errno) {
    case CURLE_COULDNT_CONNECT:
    case CURLE_COULDNT_RESOLVE_HOST:
    case CURLE_OPERATION_TIMEOUTED:
      $msg = "Could not connect to Proby ($apiBase).  Please check your internet connection and try again.";
      break;
    default:
      $msg = "Unexpected error communicating with Proby.  If this problem persists, let us know at probyapp@signalhq.com.";
    }

    $msg .= "\n\n(Network error [errno $errno]: $message)";
    throw new Proby_ApiConnectionError($msg);
  }
}
