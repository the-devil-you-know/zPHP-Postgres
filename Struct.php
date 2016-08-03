<?
declare(strict_types = 1);

namespace zPHP\Postgres;

class Struct {

	static function arrayEncode ($array) : string {
		settype($array, 'array');
		$result = [];
		foreach ($array as $t) {
			if (is_array($t))
				$result[] = self::arrayEncode($t);
			else {
				$t = str_replace('"', '\\"', $t);
				if (!is_numeric($t))
					$t = '"' . $t . '"';
				$result[] = $t;
			}
		}
		return '{' . implode(',', $result) . '}';
	}

	static function arrayDecode (string $str) : array {
		$r = trim($str, '{}');
		return $r ? str_getcsv($r) : [];
	}

	static function rangeEncode (int $from, int $to) : string {
		return '[' . $from . ',' . $to . ']';
	}

	/** @return array|null */
	static function rangeDecode (string $str) {
		if (!preg_match('/^(.)(\d+),(\d+)(.)$/', $str, $m))
			return NULL;

		return [
			$m[1] == '(' ? ++$m[2] : (int)$m[2],
			$m[4] == ')' ? --$m[3] : (int)$m[3]
		];
	}
}