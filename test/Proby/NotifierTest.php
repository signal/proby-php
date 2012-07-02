<?php

class Proby_NotifierTest extends UnitTestCase
{
  public function testStartNotification()
  {
    authorize();
    $response = Proby::sendStartNotification("cc723310a67b012fd3ed60f84703068e");
    $this->assertNull($response);
  }

  public function testFinishNotification()
  {
    authorize();
    $response = Proby::sendFinishNotification("cc723310a67b012fd3ed60f84703068e");
    $this->assertNull($response);
  }

  public function testFinishNotificationWithFailureData()
  {
    authorize();
    $response = Proby::sendFinishNotification("cc723310a67b012fd3ed60f84703068e", true, "Something bad happened");
    $this->assertNull($response);
  }
}
