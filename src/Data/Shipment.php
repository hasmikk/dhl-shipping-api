<?php

namespace DHL\Data;

/**
 * Prepare shipment detail information
 *
 * @package DHL\Data
 */
class Shipment
{

    protected static $nationalEkp;

    protected static $interNationalEkp;

    /**
     * Natanal shippment country
     * for German shipper
     * @var string
     */

    const NATIONAL_SHIPP_COUNTRY = 'DE';


    /**
     * DHL Paket product code
     * for German shipper
     * @var string
     */

    const NATIONAL_PRODUCT_CODE = 'V01PAK';

    /**
     * DHL Paket International product code
     * for German shipper
     * @var string
     */

    const INTERNATIONAL_PRODUCT_CODE = 'V53WPAK';

    /**
     * Initial shipment information
     * @var array
     */

    protected $info = [];

    /**
     * Shipment Item information
     * @var array
     */
    protected $item = [];

    /**
     * Shipment service information
     * @var array
     */
    protected $service = [];

    /**
     * Set notification information
     *
     * @var array
     */
    protected $notification = '';

    /**
     * Shipment constructor.
     *
     * @param $info
     */
    public function __construct($info)
    {
        $this->info = $info;
    }

    /**
     * Prepare shipment item information
     *
     * @param array $items Items attributes
     *
     * @return $this  Chainable
     */
    public function item($items)
    {
        $this->item['weightInKG'] = $items['weight'];

        if (isset($items['length'])) {
            $this->item['lengthInCM'] = $items['length'];
        }

        if (isset($items['width'])) {
            $this->item['widthInCM'] = $items['width'];
        }

        if (isset($items['height'])) {
            $this->item['heightInCM'] = $items['height'];
        }

        return $this;
    }

    /**
     * Get shipment items
     *
     * @return array
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * This service is an optional
     *
     * @param  array $service Array of service information
     *
     * @return $this
     */
    public function service($service)
    {
        if (isset($service['VisualCheckOfAge'])) {
            $this->service['VisualCheckOfAge'] = ['@active' => 1, '@type' => $service['VisualCheckOfAge']];
        }

        if (isset($service['PreferredLocation'])) {
            $this->service['PreferredLocation'] = ['@active' => 1, '@details' => $service['PreferredLocation']];
        }

        if (isset($service['PreferredNeighbour'])) {
            $this->service['PreferredNeighbour'] = ['@active' => 1, '@details' => $service['PreferredNeighbour']];
        }

        if (isset($service['GoGreen'])) {
            $this->service['GoGreen'] = ['@active' => $service['GoGreen']];
        }

        if (isset($service['Personally'])) {
            $this->service['Personally'] = ['@active' => $service['Personally']];
        }

        if (isset($service['CashOnDelivery'])) {
            $this->service['CashOnDelivery'] = ['@active' => 1, '@codAmount' => $service['CashOnDelivery']];
        }

        if (isset($service['AdditionalInsurance'])) {
            $this->service['AdditionalInsurance'] = ['@active' => 1, '@insuranceAmount' => $service['AdditionalInsurance']];
        }

        if (isset($service['BulkyGoods'])) {
            $this->service['BulkyGoods'] = ['@active' => $service['BulkyGoods']];
        }

        return $this;
    }

    /**
     * Get service information
     *
     * @return array
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Optional service
     *
     * @param   string $email Email for sending notification
     *
     * @return  $this
     */
    public function notify($email)
    {
        $this->notification = ['recipientEmailAddress' => $email];

        return $this;
    }

    /**
     * Get notification information
     *
     * @return array
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * Get package product
     * code depending on
     * shipping country
     *
     * @return string
     */
    public static function getProductCode($shippCountryCode)
    {
        if ($shippCountryCode == self::NATIONAL_SHIPP_COUNTRY) {
            return self::NATIONAL_PRODUCT_CODE;
        }
        return self::INTERNATIONAL_PRODUCT_CODE;
    }

    /**
     * Set package EKP
     * for national shipments
     *
     * @return string
     */

    public static function setNational($ekp)
    {
        return self::$nationalEkp = $ekp;
    }


    /**
     * Set package EKP
     * for international shipments
     *
     * @return string
     */

    public static function setInternational($ekp)
    {
        return self::$interNationalEkp = $ekp;
    }

    /**
     * Get package EKP
     * cod depending on
     * shipping country
     *
     * @return string
     */
    public static function getEkp($shippCountryCode)
    {
        if ($shippCountryCode == self::NATIONAL_SHIPP_COUNTRY) {
            return self::$nationalEkp;
        }
        return self::$interNationalEkp;
    }


    /**
     * Get shipment information
     *
     * @return array
     * @throws \Exception
     */
    public function getInfo()
    {
        if (empty($this->info)) {
            throw new \Exception('Invalid info. Set info using (new Shipper)->setInfo($info);');
        }

        $shipment = $this->info;
        $shipment['ShipmentItem'] = $this->getItem();

        if (!empty($this->getService())) {
            $shipment['Service'] = $this->getService();
        }

        $shipment['Notification'] = $this->getNotification();

        return $shipment;
    }
}
