<?php


namespace DHL\Request\Business;

use DHL\Data\Version;
use DHL\Request\RequestInterface;

/**
 * Generate Label
 *
 * @package DHL\Request\Business
 */
class Label implements RequestInterface
{
	/**
	 * Name of the service to call from wsdl api
	 *
	 * @var string
	 */
	public $serviceName = 'GetLabel';

	/**
	 * Valid shipment number to fetch label
	 *
	 * @var string
	 */
	private $shipmentNumber;

	/**
	 * Possible values:
	 *                  URL
	 *                  B64
	 *
	 * @var string
	 */
	private $labelType = 'B64';

	/**
	 * Label constructor.
	 *
	 * @param $shipmentNumber
	 */
	public function __construct($shipmentNumber)
	{
		$this->shipmentNumber = $shipmentNumber;
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
	public function toArray()
	{
		$label = [
			'Version'       => (new Version())->get(),
			'shipmentNumber' => $this->shipmentNumber,
			'labelResponseType' => $this->labelType
		];

		return $label;
	}
}
