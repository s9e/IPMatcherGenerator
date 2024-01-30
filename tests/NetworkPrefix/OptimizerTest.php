<?php

namespace s9e\IPMatcherGenerator\Tests\NetworkPrefix;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use s9e\IPMatcherGenerator\NetworkPrefix\Optimizer;

#[CoversClass('s9e\IPMatcherGenerator\NetworkPrefix\Optimizer')]
class OptimizerTest extends TestCase
{
	#[DataProvider('getOptimizeTests')]
	public function testNormalizeHostInput(array $original, array $expected): void
	{
		$this->assertEquals($expected, (new Optimizer)->optimize($original));
	}

	public static function getOptimizeTests(): array
	{
		return [
			[
				[],
				[]
			],
			[
				[
					''
				],
				[
					''
				]
			],
			[
				[
					'1010',
					'1111'
				],
				[
					'1010',
					'1111'
				]
			],
			[
				[
					'100',
					'10010',
					'1111'
				],
				[
					'100',
					'1111'
				]
			],
			[
				[
					'100',
					'10010',
					'10011',
					'1111'
				],
				[
					'100',
					'1111'
				]
			],
		];
	}
}