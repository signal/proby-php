<?php

class Proby_Notifier
{
  public static function sendNotification($taskId, $type)
  {
    $requestor = new Proby_ApiRequestor();
    $url = self::_notificationUrl($taskId, $type);
    list($response, $apiKey) = $requestor->request('post', $url);
  }

  private static function _notificationUrl($taskId, $type)
  {
    return "/api/v1/tasks/$taskId/$type.json";
  }
}
