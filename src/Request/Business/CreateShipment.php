<?php

namespace DHL\Request\Business;

use DHL\Request\RequestInterface;
use \DHL\Data\Shipment;
use \DHL\Data\Shipper;
use \DHL\Data\Receiver;
use \DHL\Data\Version;

/**
 * Get ready to Create Shipment
 *
 * @package DHL\Request\Business
 */
class CreateShipment implements RequestInterface
{
	/**
	 * Name of the service to call from wsdl api
	 * @var string
	 */
	public $serviceName = 'CreateShipmentOrder';

	/**
	 * Shipment detail
	 * @var \DHL\Data\Shipment Valid object
	 */
	private $shipment;

	/**
	 * Shipper detail
	 * @var \DHL\Data\Shipper Valid object
	 */
	private $shipper;

	/**
	 * Receiver detail
	 * @var \DHL\Data\Receiver Valid object
	 */
	private $receiver;

	/**
	 * Order Information id
	 * @var int
	 */
	private $orderId = 0;

	/**
	 * Possible values:
	 *                  URL
	 *                  B64
	 *
	 * @var string
	 */
	private $labelType = 'B64';

	/**
	 * Get shipment detail
	 *
	 * @param Shipment $shipment
	 *
	 * @return $this
	 */
	public function detail(Shipment $shipment)
	{
		$this->shipment = $shipment;

		return $this;
	}

	/**
	 * Get shipper information
	 *
	 * @param Shipper $shipper
	 *
	 * @return $this
	 */
	public function shipper(Shipper $shipper)
	{
		$this->shipper = $shipper;

		return $this;
	}

	/**
	 * Get receiver information
	 *
	 * @param Receiver $receiver
	 *
	 * @return $this
	 */
	public function receiver(Receiver $receiver)
	{
		$this->receiver = $receiver;

		return $this;
	}

	/**
	 * Set order information id as sequence number
	 *
	 * @param int $orderId
	 *
	 * @return $this
	 */
	public function setOrderId($orderId)
	{
		$this->orderId = $orderId;

		return $this;
	}

	/**
	 * Get valid label
	 *
	 * @param string $type
	 *
	 * @return $this
	 */
	public function labelType($type)
	{
		if (!in_array($type, ['URL', 'B64'])) {
			throw new \InvalidArgumentException('Wrong label type is set. You set ' . $type . '. It should be either URL or B64');
		}

		$this->labelType = $type;

		return $this;
	}

	/**
	 * Prepare the final array to post
	 *
	 * @return array
	 */
	public function toArray() {

		$shipment = [
			'Version'       => (new Version())->get(),
			'ShipmentOrder' => [
				'sequenceNumber'      => $this->orderId,
				'Shipment'            => [
					'ShipmentDetails' => $this->shipment->getInfo(),
					'Shipper'         => $this->shipper->getInfo(),
					'Receiver'        => $this->receiver->getInfo(),
					'ReturnReceiver'  => $this->shipper->getInfo(),
				],
				'PrintOnlyIfCodeable' => ['@active' => 0],
				'labelResponseType' => $this->labelType,
			]
		];

		return $shipment;
	}
}
