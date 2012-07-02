<?php

class Proby_InvalidRequestErrorTest extends UnitTestCase
{
  public function setUp() {
    authorize();
  }

  public function testInvalidObject()
  {
    try {
      Proby::sendStartNotification("860861c0a66f012fd3dd60f84703068e");
      $this->fail("Did not raise error");
    } catch (Proby_InvalidRequestError $e) {
      $this->assertEqual(404, $e->getHttpStatus());
    }
  }

  public function testBadData()
  {
    try {
      Proby::sendStartNotification("8d541230a66f012fd3dd60f84703068e");
      $this->fail("Did not raise error");
    } catch (Proby_InvalidRequestError $e) {
      $this->assertEqual(400, $e->getHttpStatus());
      $this->assertEqual("TESTING: An invalid argument was provided", $e->getMessage());
    }
  }
}
