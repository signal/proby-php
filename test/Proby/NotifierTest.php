<?php

class Proby_NotifierTest extends UnitTestCase
{
  public function setUp() {
    authorize();
  }

  public function testStartNotification()
  {
    $response = Proby::sendStartNotification("cc723310a67b012fd3ed60f84703068e");
    $this->assertNull($response);
  }

  public function testFinishNotification()
  {
    $response = Proby::sendFinishNotification("cc723310a67b012fd3ed60f84703068e");
    $this->assertNull($response);
  }

  public function testFinishNotificationWithFailureData()
  {
    $response = Proby::sendFinishNotification("cc723310a67b012fd3ed60f84703068e", true, "Something bad happened");
    $this->assertNull($response);
  }
}
