<?php

$ok = @include_once(dirname(__FILE__).'/simpletest/autorun.php');
if (!$ok) {
  echo "MISSING DEPENDENCY: The Stripe API test cases depend on SimpleTest. ".
       "Download it at http://www.simpletest.org/, and either install it ".
       "in your PHP include_path or put it in the test/ directory.\n";
  exit(1);
}


function authorize()
{
  Proby::setApiKey('cfd28910a66e012fd3dd60f84703068e');
}


require_once(dirname(__FILE__) . '/../lib/Proby.php');

require_once(dirname(__FILE__) . '/Proby/ApiRequestorTest.php');
require_once(dirname(__FILE__) . '/Proby/AuthenticationErrorTest.php');
require_once(dirname(__FILE__) . '/Proby/ErrorTest.php');
require_once(dirname(__FILE__) . '/Proby/InvalidRequestErrorTest.php');
require_once(dirname(__FILE__) . '/Proby/NotifierTest.php');

?>
