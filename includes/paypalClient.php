<?php

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;

require_once __DIR__ . '/../../vendor/autoload.php';

function paypalClient()
{
    $clientId = 'TU_CLIENT_ID_SANDBOX';
    $clientSecret = 'TU_CLIENT_SECRET_SANDBOX';

    $environment = new SandboxEnvironment($clientId, $clientSecret);
    return new PayPalHttpClient($environment);
}
