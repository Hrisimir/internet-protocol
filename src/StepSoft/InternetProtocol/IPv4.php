<?php

namespace StepSoft\InternetProtocol;

/**
 * Class IPv4 - Contains tools for manipulating and gathering information on IPv4 addresses
 * @package StepSoft\InternetProtocol
 */
class IPv4 {

	const REGEX_PARTIAL = '/\b(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.?){1,3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b/';

	/**
	 * @var string
	 */
	protected $ipAddress;

	/**
	 * @var array
	 */
	protected $ipRanges;

	/**
	 * Construct an IP utility instance, provide a full or partial IP address.
	 *  E.g. .56.1 | 127.0 | 192.168.56.2 | 192.100.
	 *
	 * @param $ip4Address
	 */
	public function __construct($ip4Address) {
		$ip4Address = trim($ip4Address);

		// Remove possible wildcard.
		if (strpos($ip4Address, '*') !== false) {
			$ip4Address = str_replace("*", "", $ip4Address);
		}

		// remove any any pre- or post-fixing full stops.
		if (strpos($ip4Address,'.') === 0) {
			$ip4Address = substr($ip4Address, 1);
		}

		if (strrpos($ip4Address,'.') === (strlen($ip4Address)-1)) {
			$ip4Address = substr($ip4Address, 0, strlen($ip4Address) - 1);
		}


		$this->ipAddress = $ip4Address;
	}

	/**
	 * Determines if an IP address is of a valid structure.
	 *
	 * @return bool
	 */
	public function isValid() {
		return (bool) preg_match(self::REGEX_PARTIAL, $this->ipAddress);
	}

	/**
	 * Get the minimum and maximum IP address from a full or partial IP address.
	 * E.g.:  192.168 === [192.168.0.0 -> 192.168.255.255]
	 *
	 * @throws \Exception  if IP Address is invalid.
	 * @return array
	 */
	public function getRange() {
		if (is_array($this->ipRanges)) {
			return $this->ipRanges;
		}

		if (! $this->isValid()) {
			throw new \Exception(sprintf("IP Address provided was not a valid IP v4 address: ", $this->ipAddress));
		}

		$dots = 4 - (count(explode(".", $this->ipAddress)));
		$retvalue['low'] = $this->ipAddress;
		$retvalue['high'] = $this->ipAddress;

		for ($i = 0; $i < $dots; $i++) {
			$retvalue['low'] .= ".0";
			$retvalue['high'] .= ".255";
		}

		return $this->ipRanges = $retvalue;
	}

	/**
	 * Get the highest IP address available on partial IP address.
	 *
	 * @throws \Exception  if IP Address is invalid.
	 * @return string
	 */
	public function getHighRange() {
		if (is_array($this->ipRanges)) {
			return $this->ipRanges['high'];
		}

		$this->getRange();
		return $this->ipRanges['high'];
	}

	/**
	 * Get the lowest IP address available on partial IP address.
	 *
	 * @throws \Exception  if IP Address is invalid.
	 * @return string
	 */
	public function getLowRange() {
		if (is_array($this->ipRanges)) {
			return $this->ipRanges['low'];
		}

		$this->getRange();
		return $this->ipRanges['low'];
	}

	/**
	 * Same is ip2long()
	 *
	 * @return int
	 */
	public function toLong() {
		return ip2long($this->ipAddress);
	}
} 