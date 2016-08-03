<?
declare(strict_types = 1);

namespace zPHP\Postgres;

class FTS {

	static function prepStr (string $str, string $delimiter = '|') : string {
		$str  = preg_replace('/[^a-zа-яё0-9\s\-]/ui', '', trim($str));
		$aSub = preg_split('/\s+/', $str);
		return implode($delimiter, $aSub);
	}
}