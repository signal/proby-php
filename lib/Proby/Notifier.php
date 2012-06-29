<?php

class Proby_Notifier
{
  public function sendStartNotification($taskId)
  {
    return $this->sendNotification($taskId, 'start');
  }

  public function sendFinishNotification($taskId)
  {
    return $this->sendNotification($taskId, 'finish');
  }

  private function sendNotification($taskId, $type)
  {
    $requestor = new Proby_ApiRequestor();
    $url = $this->notificationUrl($taskId, $type);
    list($response, $apiKey) = $requestor->request('post', $url);
  }

  private function notificationUrl($taskId, $type)
  {
    return "/api/v1/tasks/$taskId/$type.json";
  }
}
