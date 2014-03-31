# StepSoft - Internet Protocol Package


### Purpose
Package is used for checking and manipulating IP address in their full form or in partial form. 

### Installation
Simply add the the following line to your **require:{ ... }** clausing your projects **composer.json** file.



	"stepsoft/internet-protocol": "dev"



### Class "IPv4"
As the class name suggests it for use with IPv4 address. 

**Generating a Range of IP address from partial or full IPs**

	$ip = new \StepSoft\InternetProtocol\IPv4('192.168');
	// Returns '192.168.0.0'
	$lowRange = $ip->getLowRange();
	
	// Returns '192.168.255.255'
	$highRange = $ip->getHighRange();
	
	// Returns ['low' => '192.168.0.0', 'high' => '192.168.255.255']
	$range = $ip->getRange();
	
You can also provide pre and post fixing fullstops to the IP address, as well as identifying "*" wildcard. For example:

	192.168.* 	=== 192.168.0.0 -> 192.168.255.255
	.192.168.* 	=== 192.168.0.0 -> 192.168.255.255
	.192.168 	=== 192.168.0.0 -> 192.168.255.255
	.192.168. 	=== 192.168.0.0 -> 192.168.255.255
	10.			=== 10.0.0.0    -> 10.255.255.255
	.10			=== 10.0.0.0    -> 10.255.255.255	
	192 (1..255)=== 192.0.0.0   -> 192.255.255.255
	
**Note:**
The method isValid() is called when attempting to get a range, if this has not been validated prior to calling the method, it will throw an **\Exception**.

