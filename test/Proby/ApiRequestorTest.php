<?php

class Proby_ApiRequestorTest extends UnitTestCase
{
  public function testEncode()
  {
    $a = array('my' => 'value', 'that' => array('your' => 'example'), 'bar' => 1, 'baz' => null);
    $enc = Proby_APIRequestor::encode($a);
    $this->assertEqual($enc, 'my=value&that%5Byour%5D=example&bar=1');

    $a = array('that' => array('your' => 'example', 'foo' => null));
    $enc = Proby_APIRequestor::encode($a);
    $this->assertEqual($enc, 'that%5Byour%5D=example');
  }

  public function testFetchErrorMessage()
  {
    $errorMessage = Proby_APIRequestor::fetchErrorMessage('{ "request" : "/foo/bar", "message" : "This is the error message" }');
    $this->assertEqual($errorMessage, 'This is the error message');

    $errorMessage = Proby_APIRequestor::fetchErrorMessage('not valid json');
    $this->assertNull($errorMessage);

    $errorMessage = Proby_APIRequestor::fetchErrorMessage('');
    $this->assertNull($errorMessage);

    $errorMessage = Proby_APIRequestor::fetchErrorMessage(null);
    $this->assertNull($errorMessage);
  }
}
