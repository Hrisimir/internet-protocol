<?php

namespace StepSoft\InternetProtocol;

use Request;

/**
 * Class UserIp
 *
 * Useful for a single point of gaining a users real IP address from the Request in Laravel.
 *
 * @package StepSoft\InternetProtocol
 */
class UserIp {

	/**
	 * Get a user's IP from the Request, checking HTTP proxy/Load balancer followed by the PHP remote address
	 * if no proxy information is available, if $asLong is set to true, you'll be returned a integer value
	 * of the IP address.
	 *
	 * @param bool $asLong Whether to return the IP address as a Long Integer. (Default: false)
	 * @return int|string
	 */
	public function fetchRealIpAddress($asLong = false) {

		$server_headers = Request::server();

		$ip = null;

		if (array_key_exists("HTTP_X_FORWARDED_FOR", $server_headers) && $server_headers["HTTP_X_FORWARDED_FOR"]) {
			$ips = explode(',', $server_headers["HTTP_X_FORWARDED_FOR"]);
			foreach ($ips as $address) {
				$trimmed = trim($address);
				if (filter_var($trimmed, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) {
					$ip = $trimmed;
					break;
				}
			}
		}

		// No ip found?
		if (!$ip) {
			$ip = $server_headers["REMOTE_ADDR"];
		}

		// Whether to return the integer value
		if ($asLong) {
			$ip = ip2long($ip);
		}

		return $ip;
	}

} 