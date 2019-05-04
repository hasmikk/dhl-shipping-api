<?php


namespace DHL\Data;

/**
 * Prepare shipper data
 *
 * @package DHL\Data
 */
class Shipper
{
	/**
	 * Initial shipment information
	 * @var array
	 */
	protected $info;

	/**
	 * Shipper constructor.
	 *
	 * @param $info
	 */
	public function __construct($info)
	{
		$this->info = $info;
	}

	/**
	 * Get shipper detail
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function getInfo()
	{
		if (empty($this->info))
		{
			throw new \Exception('Invalid info. Set info using (new Shipper)->setInfo($info);');
		}

		$shipper = [
			'Name' => [
				'name1' => $this->info['company_name'],
				// 'name2' => '',
				// 'name3' => ''
			],
			'Address' => [
				'streetName'             => $this->info['street_name'],
				'streetNumber'           => $this->info['street_number'],
				'addressAddition'        => isset($this->info['address_addition']) ? $this->info['address_addition'] : '',
				'dispatchingInformation' => $this->info['comment'],
				'zip'                    => $this->info['zip'],
				'city'                   => $this->info['city'],
				'Origin'                 => [
					'country'        => 'Deutschland',
					'countryISOCode' => 'DE',
					// 'state'          => '' // optional
				]
			],
			'Communication' => [
				'phone' => $this->info['phone'],
				'email' => $this->info['email'],
				'contactPerson' => $this->info['contact_person']
			]
		];

		return $shipper;
	}
}
