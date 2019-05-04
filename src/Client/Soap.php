<?php

namespace DHL\Client;

use DHL\Config;
use \DHL\Request\RequestInterface as Request;

/**
 * DHL API SOAP Client
 *
 * @package DHL\Client
 */
class Soap
{
    /**
     * DHL Soap Api config
     *
     * @var config
     */
    private $config = [
        'user' => null,
        'signature' => null,
        'ekp' => null,
        'apiUser' => null,
        'apiPassword' => null,
        'log' => true
    ];

    /**
     * DHL Soap Api url
     *
     * @var string
     */
    protected $apiUrl = 'https://cig.dhl.de/cig-wsdls/com/dpdhl/wsdl/geschaeftskundenversand-api/2.2/geschaeftskundenversand-api-2.2.wsdl';

    /**
     * DHL Soap Api authentication sandbox location
     *
     * @var string
     */
    protected $sandboxUrl = 'https://cig.dhl.de/services/sandbox/soap';

    /**
     * DHL Soap Api authentication sandbox location
     *
     * @var string
     */
    protected $productionUrl = 'https://cig.dhl.de/services/production/soap';

    /**
     * DHL Soap Api connection object
     *
     * @var object
     */
    protected $client;

    /**
     * Using Sandbox environment or production
     *
     * @var bool
     */


    protected $sandbox;

    /**
     * SOAP Client constructor
     *
     * @param   boolean $sandbox use sandbox or production environment
     */


    public function __construct($sandbox = false, array $config)
    {
        $this->sandbox = $sandbox;

        foreach ($config as $key => $value) {
            if (array_key_exists($key, $this->config)) {
                $this->config[$key] = $value;
            }
        }

        $required_keys = [
            'user', 'signature', 'apiUser', 'apiPassword'
        ];

        foreach ($required_keys as $key) {
            if (is_null($this->config[$key])) {
                throw new Exception('Required field ' . $key . ' is not set');
            }
        }
    }


    /**
     * Get authentication location based for sandbox or production
     *
     * @return string
     */
    protected function getAuthUrl()
    {
        return ($this->sandbox) ? $this->sandboxUrl : $this->productionUrl;
    }

    /**
     * Get valid soap authentication header
     *
     * @return \SoapHeader
     */
    public function getHeader()
    {
        $params = array(
            'user' => $this->config['user'],
            'signature' => $this->config['signature'],
            'type' => 0
        );


        return new \SoapHeader('http://dhl.de/webservice/cisbase', 'Authentification', $params);
    }

    /**
     * Execute final soap function
     *
     * @param  $request  Valid shipment request object
     *
     * @return mixed
     */
    public function call($request)
    {

        $params = [
            'login' => $this->config['apiUser'],
            'password' => $this->config['apiPassword'],
            'location' => $this->getAuthUrl(),
            'trace' => 1
        ];

        $this->client = new \SoapClient($this->apiUrl, $params);
        $this->client->__setSoapHeaders($this->getHeader());

        // Get service name
        $serviceName = $request->serviceName;

        // Send service request
        $response = $this->client->$serviceName($request->toArray());

        return $response;
    }
}
