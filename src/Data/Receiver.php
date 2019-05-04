<?php


namespace DHL\Data;

/**
 * Prepare receiver information
 *
 * @package DHL\Data
 */
class Receiver
{
	/**
	 * Initial shipment information
	 * @var array
	 */
	protected $info;

	/**
	 * Receiver constructor.
	 *
	 * @param $info
	 */
	public function __construct($info)
	{
		$this->info = $info;
	}

	/**
	 * Get receiver infromation
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

		$receiver = [
			'name1' => $this->info['name'],
			'Address' => [
				// 'name2' => '',       // Optional
				// 'name3' => '',       // Optional
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

		return $receiver;
	}
}
