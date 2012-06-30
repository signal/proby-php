<?php

class Proby_Notifier
{
  public static function sendNotification($taskId, $type)
  {
    $requestor = new Proby_ApiRequestor();
    $url = Proby_Notifier::notificationUrl($taskId, $type);
    list($response, $apiKey) = $requestor->request('post', $url);
  }

  private static function notificationUrl($taskId, $type)
  {
    return "/api/v1/tasks/$taskId/$type.json";
  }
}
