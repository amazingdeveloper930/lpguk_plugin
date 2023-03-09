<?php
error_reporting(E_ALL ^ E_NOTICE);
// include the SOAP classes
require_once('lib/nusoap.php');
// define parameter array (RBA ID)
$param = array('DATABASE'=>'ACCOUNT','INDEXFILE'=>'ACCOUNT','SEEKVALUE'=>'RBA001');
// define path to server application
$serverpath ='http://82.68.251.73:81';
//define method namespace
$namespace="urn:test";
// create client object
$client = new soapclient($serverpath);
// make the call
$accres = $client->call('Rba_GetRecordAsVars',$param,$namespace);
// if a fault occurred, output error info
if (isset($fault)) {
        print "Error: ". $fault;
        }
else if (empty($accres)) {
        print "The account is not in the database.";
} else {
        // otherwise output the result
        //print_r($accres);
        echo "I found an account ID: $accres[INVACCT] belonging to: $accres[NAME] of $accres[ROAD],$accres[TOWN], $accres[COUNTY]<br /><br />
        I am not sure what the other guff is yet, but it is below for debugging<hr />";
        print_r($accres);
        }
// kill object
// Display the request and response
// Display the debug messages
echo '<h2>Debug</h2>';
echo '<pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';

echo '<h2>Request</h2>';
echo '<pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
echo '<h2>Response</h2>';
echo '<pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';

unset($client);
?>
