<?php
function send_money($key,$amount,$receiver = 'lsnpre_1235310181_per@yahoo.com') {
    // Include the service wrapper class and the constants file
    require_once 'lib/AdaptivePayments.php';
    //require_once 'web_constants.php';
    
    // Create request object
    $payRequest = new PayRequest();
    $payRequest->actionType = "PAY";
    $payRequest->cancelUrl = 'http://flux.lolwut.net/flux/website/account_home.php' ;
    $payRequest->returnUrl = 'http://flux.lolwut.net/flux/website/account_home.php';
    $payRequest->clientDetails = new ClientDetailsType();
    $payRequest->clientDetails->applicationId ='APP-80W284485P519543T';
    $payRequest->clientDetails->deviceId = '127001';
    $payRequest->clientDetails->ipAddress = '127.0.0.1';
    $payRequest->currencyCode = 'EUR';
    $payRequest->senderEmail = 'seller_1309852781_biz@yahoo.com';
    $payRequest->requestEnvelope = new RequestEnvelope();
    $payRequest->requestEnvelope->errorLanguage = 'en_US';
    $payRequest->trackingId = $key;
    $payRequest->ipnNotificationUrl = 'http://flux.lolwut.net/flux/pools/paypalPool/paypal/IPN.php';
    
    //$payRequest->fundingConstraint = "BALANCE";
    			
    $receiver1 = new receiver();
    $receiver1->email = $receiver;
    $receiver1->amount = $amount;
    //paymentType=personal is very important otherwise we pay more fees
    //but there's still a 1% for cross-country transactions apparently
    //not counting the 2.5% currency conversion fee. D;
    $receiver1->paymentType = "PERSONAL";
    	
    $payRequest->receiverList = array($receiver1);
    
    
    // Create service wrapper object
    $ap = new AdaptivePayments();
    
    // invoke business method on service wrapper passing in appropriate request params
    $response = $ap->Pay($payRequest);
    
    // Check response
    if(strtoupper($ap->isSuccess) == 'FAILURE')
    {
    	$soapFault = $ap->getLastError();
    	echo "Transaction Pay Failed: error Id: ";
    	if(is_array($soapFault->error)) {
    		echo $soapFault->error[0]->errorId . ", error message: " . $soapFault->error[0]->message ;
    	} else {
    		echo $soapFault->error->errorId . ", error message: " . $soapFault->error->message ;
    	}
    } else {
    	$token = $response->payKey;
    	//echo "Transaction Successful! PayKey is $token \n";
    	header( 'Location: http://flux.lolwut.net/flux/website/account_home.php' );
    }

}

?>
