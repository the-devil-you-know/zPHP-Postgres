<?
namespace ZLibs\Postgres;

class FTS {

	/**
	 * Prep string for full text search
	 *
	 * @param string $_str
	 * @param string $_delimiter
	 * @return string
	 */
	static function prepStr ($_str, $_delimiter = '|') {
		$str  = preg_replace('/[^a-zа-яё0-9\s\-]/ui', '', trim($_str));
		$aSub = preg_split('/\s+/', $str);
		return implode($_delimiter, $aSub);
	}

}