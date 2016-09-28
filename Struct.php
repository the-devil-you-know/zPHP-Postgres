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

	static function rangeDecode (string $str) : ? array {
		if (!preg_match('/^(.)(\d+),(\d+)(.)$/', $str, $m))
			return NULL;

		return [
			$m[1] == '(' ? ++$m[2] : (int)$m[2],
			$m[4] == ')' ? --$m[3] : (int)$m[3]
		];
	}

	/** @param array $aLatLon [[0,0],[4,0],[4,4]] */
	static function polygonEncode (array $aLatLon) : string {
		$a = [];
		foreach ($aLatLon as $latLon)
			$a[] = '(' . (float)$latLon[1] . ',' . (float)$latLon[0] . ')';
		return '(' . implode(',', $a) . ')';
	}

	/** @param string $strPolygon ((0,0),(4,0),(4,4),(0,4))) */
	static function polygonDecode (string $strPolygon) : array {
		preg_match_all('/\(([0-9.]+),([0-9.]+)\)/', $strPolygon, $m, PREG_SET_ORDER);
		$a = [];
		foreach ($m as $coord) {
			$a[] = [
				(float)$coord[2],
				(float)$coord[1]
			];
		}
		return $a;
	}
}