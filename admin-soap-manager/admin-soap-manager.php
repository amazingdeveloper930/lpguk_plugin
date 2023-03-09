<?php
/**
* Plugin Name: Admin Soap Manager
* Plugin URI: https://newsite.lpguk.info/
* Description: This is one which help user manage soap.
* Version: 1.0
* Author: Lucky
* Author URI: https://newsite.lpguk.info/
**/


// echo "!23234534";

function searchAdditionalKey($fields, $value)
{
    foreach($fields as $key => $field)
    {
        if ( $field['name'] === $value )
            return $field['key'];
    }
    return false;
}


require_once('soap-config.php');
require 'lib/vendor/autoload.php';

require_once("lib/nuSoap/lib/nusoap.php");

use Benhawker\Pipedrive\Pipedrive;



add_action("gform_after_submission_2", "after_submission_func", 10, 2);

function after_submission_func($entry, $form)
{

//   $params = array(
//     'SALUTATION'=>"",
//     'FIRSTNAME'=>$_POST['input_1'],
//     'LASTNAME'=>$_POST['input_2'],
//     'EMAIL'=>$_POST['input_3'],
//     'PHONE'=> $_POST['input_18'],
//     'MOBILE'=>"",
//     'COMPANY'=>"",
    
//     'ROAD'=>$_POST['input_17_1'],
//     'DISTRICT'=>$_POST['input_17_3'],
//     'TOWN'=>$_POST['input_17_2'],
//     'COUNTY'=>$_POST['input_17_4'],
//     'POSTCODE'=>$_POST['input_17_5'],
//     'TYPE'=>SOAP_TYPE,
//     'DELIVER_COMPANY'=>"",
//     'DELIVER_ROAD'=>"",
//     'DELIVER_DISTRICT'=>"",
//     'DELIVER_TOWN'=>"",
//     'DELIVER_COUNTY'=>"",
//     'DELIVER_POSTCODE'=>"",
//     'USER1'=>"Message : ",
//     'USER2'=>"Current supplier: " . $_POST['input_8'],
//     'USER3'=>"Contract date: " . $_POST['input_9'],
//     'USER4'=>"Annual usage : " . $_POST['input_11'],
//     'USER5'=>"Cost per litre: " . $_POST['input_12'],
//     'USER6'=>"",
//     'DOCUMENT_NAME'=>"",
//     'DOCUMENT_CONTENTS'=>"",
//     'GDPR_OPTIN' => $_POST['input_13_1']
//   );


$roadAndTown = explode(",",$_POST['input_17_2']);

 $params = array(
    'SALUTATION'=>"",
    'FIRSTNAME'=>$_POST['input_1'],
    'LASTNAME'=>$_POST['input_2'],
    'EMAIL'=>$_POST['input_3'],
    'PHONE'=> $_POST['input_18'],
    'MOBILE'=>"",
    'COMPANY'=>"",
    
    'ROAD'=>$roadAndTown[0],
    'DISTRICT'=>$_POST['input_17_4'],
    'TOWN'=>count($roadAndTown) > 1? $roadAndTown[1] : '',
    'COUNTY'=>$_POST['input_17_3'],
    'POSTCODE'=>$_POST['input_17_5'],
    'TYPE'=>SOAP_TYPE,
    'DELIVER_COMPANY'=>"",
    'DELIVER_ROAD'=>"",
    'DELIVER_DISTRICT'=>"",
    'DELIVER_TOWN'=>"",
    'DELIVER_COUNTY'=>"",
    'DELIVER_POSTCODE'=>"",
    'USER1'=>"Message : ",
    'USER2'=>"Current supplier: " . $_POST['input_8'],
    'USER3'=>"Contract date: " . $_POST['input_9'],
    'USER4'=>"Annual usage : " . $_POST['input_11'],
    'USER5'=>"Cost per litre: " . $_POST['input_12'],
    'USER6'=>"",
    'DOCUMENT_NAME'=>"",
    'DOCUMENT_CONTENTS'=>"",
    'GDPR_OPTIN' => $_POST['input_13_1']
  );
  
  
  
  
  
    if($_POST['input_13_1'] == 'Y' || $_POST['input_13_1'] == 'y')
    $params['GDPR_OPTIN'] = 'Y';
  if($_POST['input_13_1'] == 'N' || $_POST['input_13_1'] == 'n')
    $params['GDPR_OPTIN'] = 'N';
$client = new nusoap_client(SOAPSERVER);
$customer_record_response = $client->call(SOAP_METHOD_ADD_CUSTOMER, $params, "urn:test");

if(is_numeric($_POST['input_11']) && is_numeric($_POST['input_12'])){
    $_SESSION['annual_usage'] = $_POST['input_11'];
    $_SESSION['cost_litre'] = $_POST['input_12'];
}
$_SESSION['user_name'] = $params['FIRSTNAME'] . " " . $params['LASTNAME'];
$_SESSION['user_postcode'] = $params['POSTCODE'];
$_SESSION['user_email'] = $params['EMAIL'];
$_SESSION['user_address'] = $params['ROAD'] . "_" . $params['DISTRICT'] . "_" .  $params['TOWN'] . "_" .$params['COUNTY'];
$_SESSION['user_phone'] = $params['PHONE'];

// try {
//   $pipedrive = new Pipedrive('3c86c5608343672983528423b0cf1a7aba1e9219');

//   $personFields = $pipedrive->personFields()->getAll();

//   $person = array(
//     'name'=> $_POST['input_1'] . " " . $_POST['input_2'],
//     'email' => $_POST['input_3'],
//     'phone' => ""
//   );

//   if($personFields['success'] === TRUE) {
//     $fields = $personFields['data'];

//     $key_address = searchAdditionalKey($fields, 'Address');
//     $key_postcode = searchAdditionalKey($fields, 'Postcode');
//     $key_current_supplier = searchAdditionalKey($fields, 'Current Supplier');
//     $key_annual_usage = searchAdditionalKey($fields, 'Annual Usage');
//     $key_contract_date = searchAdditionalKey($fields, 'Contract Date');

//     $person[$key_address]= $_POST['input_17_1'];
//     $person[$key_postcode]= $_POST['input_17_5'];
//     $person[$key_current_supplier]= $_POST['input_8'];
//     $person[$key_annual_usage]= $_POST['input_11'];
//     $person[$key_contract_date]= $_POST['input_9'];
//   }

//   $person = $pipedrive->persons()->add($person);

//   if($person['success'] == 1) {
//     $person_id = $person['data']['id'];

//     $deal['title'] = 'LPGUK.INFO - ';
//     if(!empty($customer_record_response['ACCOUNT'])) {
//       $deal['title'] .= $customer_record_response['ACCOUNT'];
//     }
//     $deal['person_id'] = $person['data']['id'];
//     $deal['add_time'] =  date('Y-m-d H:i:s');
//     $deal['stage_id'] =  29; //WEB ENQUIRY

//     $dealRes = $pipedrive->deals()->add($deal);
//   }
// } catch (Exception $exception) {
//   error_log("Caught $exception");
// }


if ($client->fault) { // soap failed
echo "fail";
    $mail_subject = "SOAP FAULT: USER ENQUIRY DATA DID NOT ENTER RBA SYSTEM";

} else if(empty($customer_record_response['ACCOUNT'])){ // soap no response
    // echo "no response";
    $mail_subject = 'SOAP FAULT - NO RESPONSE FROM SERVER';

} else { // soap success
    // echo 'success';
    $_SESSION['user_verified'] = 'true';
    
    
}




}


