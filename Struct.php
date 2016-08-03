<?
namespace ZLibs\Postgres;

class Struct {

	/**
	 * Convert php array to pg array
	 *
	 * @param array $_array
	 * @param bool  $_isNull
	 * @return string
	 */
	static function arrayEncode ($_array) {
		settype($_array, 'array');
		$result = [];
		foreach ($_array as $t) {
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

	/**
	 * Convert pg array to php array
	 *
	 * @param string $_str
	 * @return array
	 */
	static function arrayDecode ($_str) {
		$r = trim($_str, '{}');
		return $r ? str_getcsv($r) : [];
	}

	/**
	 * Convert pg range to php array
	 *
	 * @param int $_from
	 * @param int $_to
	 * @return string [from,to]
	 */
	static function rangeEncode ($_from, $_to) {
		return '[' . $_from . ',' . $_to . ']';
	}

	/**
	 * Convert pg range to php array
	 *
	 * @param string $_str
	 * @return array|null [int `from`, int `to`]
	 */
	static function rangeDecode ($_str) {
		if (!preg_match('/^(.)(\d+),(\d+)(.)$/', $_str, $m))
			return NULL;

		return [
			$m[1] == '(' ? ++$m[2] : (int)$m[2],
			$m[4] == ')' ? --$m[3] : (int)$m[3]
		];
	}
}