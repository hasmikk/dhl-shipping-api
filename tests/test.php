<?php
require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

/**
 * Example is for sandbox mode and
 * requires DHL developer account
 *
 * Send shipment data to service
 * and request a label for the shipment
 */

use DHL\Data\Shipper;
use DHL\Data\Receiver;
use DHL\Data\Shipment as ShipmentDetail;
use \DHL\Request\Business\CreateShipment;
use \DHL\Request\Business\Label;


/**
 * @param Shipper $shipper
 *
 * @return $this
 */

$shipper = new Shipper(
    [
        'company_name' => 'Test company',
        'street_name' => 'Mühlstr',
        'street_number' => '8',
        'zip' => '88085',
        'city' => 'Langenargen',
        'email' => 'test@gmail.com',
        'phone' => '037493688383',
        'contact_person' => 'Loren	Hanson',
        'comment' => 'some notes ',
    ]
);


$customer_details = [
    'name' => 'Elijah Waters',
    'street_name' => 'Krefelder Str.',
    'street_number' => '17',
    'zip' => 47441,
    'city' => 'Moers',
    'email' => 'test@gmail.com',
    'phone' => '55678894',
    'contact_person' => 'Elijah	Waters',
    'comment' => '',
];

$receiver = new Receiver($customer_details);

$detail = new ShipmentDetail(
    [
        'product' => 'V01PAK',
        'accountNumber' => '22222222220101',
        'shipmentDate' => date('Y-m-d'),
    ]
);


// weight should be in KG

$detail->item(['weight' => 0.35])
    ->notify('user@hello.dk');


$shipment = new CreateShipment();

$shipment->setOrderId(895674)
    ->detail($detail)
    ->shipper($shipper)
    ->receiver($receiver)
    ->labelType('B64');

$client = new \DHL\Client\Soap(true,
    [
        'user' => '2222222222_01',
        'signature' => 'pass',
        'ekp' => '22222222220101',
        'apiUser' => '*****', // your DH developer account ID
        'apiPassword' => '******', // your DH developer account password
        'log' => true
    ]);

$response = $client->call($shipment);

if ($response->Status->statusCode == 0 && $response->Status->statusText == 'ok') {

    $shipmentNumber = $response->CreationState->LabelData->shipmentNumber;
    $label = new Label($shipmentNumber);
    $response = $client->call($label);
    $decoded = $response->LabelData->labelData;
    file_put_contents($file, $decoded);
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename=' . $shipmentNumber . '.pdf');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . strlen($decoded));
    ob_clean();
    flush();
    echo $decoded;
    exit;

} else {
    echo $response->Status->statusCode . "<br>";
    echo $response->Status->statusText . "<br>";
    echo "<pre>";
    var_dump($response);
}