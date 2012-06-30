<?php

class Proby_Notifier
{
  public static function sendNotification($taskId, $type, $params=null)
  {
    $requestor = new Proby_ApiRequestor();
    $url = self::_notificationUrl($taskId, $type);
    list($response, $apiKey) = $requestor->request('post', $url, $params);
  }

  private static function _notificationUrl($taskId, $type)
  {
    return "/api/v1/tasks/$taskId/$type";
  }
}
