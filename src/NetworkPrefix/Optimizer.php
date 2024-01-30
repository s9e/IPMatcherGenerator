<?php declare(strict_types=1);

/**
* @package   s9e\IPMatcherGenerator
* @copyright Copyright (c) The s9e authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\IPMatcherGenerator\NetworkPrefix;

use const SORT_STRING;
use function array_values, count, sort, str_starts_with;

class Optimizer
{
	public function optimize(array $prefixes): array
	{
		sort($prefixes, SORT_STRING);

		$max = count($prefixes) - 1;
		$i   = -1;
		while (++$i < $max)
		{
			if (!isset($prefixes[$i]))
			{
				continue;
			}

			$j = $i;
			while (++$j < $max && str_starts_with($prefixes[$j], $prefixes[$i]))
			{
				unset($prefixes[$j]);
			}
		}

		return array_values($prefixes);
	}
}