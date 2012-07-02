<?php

class Proby_InvalidRequestErrorTest extends UnitTestCase
{
  public function testInvalidObject()
  {
    authorize();
    try {
      Proby::sendStartNotification("860861c0a66f012fd3dd60f84703068e");
    } catch (Proby_InvalidRequestError $e) {
      $this->assertEqual(404, $e->getHttpStatus());
    }
  }

  public function testBadData()
  {
    authorize();
    try {
      Proby::sendStartNotification("8d541230a66f012fd3dd60f84703068e");
    } catch (Proby_InvalidRequestError $e) {
      $this->assertEqual(400, $e->getHttpStatus());
    }
  }
}
