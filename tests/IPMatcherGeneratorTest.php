<?php

namespace s9e\IPMatcherGenerator\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use s9e\IPMatcherGenerator\AddressType\IPv4;
use s9e\IPMatcherGenerator\AddressType\IPv6;
use s9e\IPMatcherGenerator\IPMatcherGenerator;

#[CoversClass('s9e\IPMatcherGenerator\IPMatcherGenerator')]
class IPMatcherGeneratorTest extends TestCase
{
	#[DataProvider('getGetRegexpIPv4Tests')]
	public function testGetRegexpIPv4(string $expected, array $cidrList): void
	{
		$generator = new IPMatcherGenerator(addressType: new IPv4);

		$this->assertEquals($expected, $generator->getRegexp($cidrList));
	}

	public static function getGetRegexpIPv4Tests(): array
	{
		return [
			[
				'/^127\\./',
				['127.0.0.0/8']
			],
			[
				'/^127\\.0\\.0\\.1$/',
				['127.0.0.1/32']
			],
			[
				'/^103\\.2[12]\\./',
				['103.21.0.0/16', '103.22.0.0/16']
			],
		];
	}

	#[DataProvider('getGetRegexpIPv6Tests')]
	public function testGetRegexpIPv6(string $expected, array $cidrList): void
	{
		$generator = new IPMatcherGenerator(addressType: new IPv6);

		$this->assertEquals($expected, $generator->getRegexp($cidrList));
	}

	public static function getGetRegexpIPv6Tests(): array
	{
		return [
			[
				'/^2405:(?:81|b5)00:/i',
				['2405:b500::/32', '2405:8100::/32']
			],
		];
	}
}