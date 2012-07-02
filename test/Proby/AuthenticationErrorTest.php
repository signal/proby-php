<?php

class Proby_AuthenticationErrorTest extends UnitTestCase
{
  public function testInvalidCredentials()
  {
    Proby::setApiKey('invalid');
    try {
      Proby::sendStartNotification('some_task_id');
      $this->fail("Did not raise error");
    } catch (Proby_AuthenticationError $e) {
      $this->assertEqual(401, $e->getHttpStatus());
    }
  }
}
