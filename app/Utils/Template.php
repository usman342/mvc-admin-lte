<?php

namespace App\Utils;

class Template {

	static $blocks = array();
	static $cacheEnabled = false;

	static $folder;

	static function cachePath($path = null) {
		return $path ?? $_ENV['CACHE_DIR'];
	}

	static function setfolder($folder) {
		// normalize the internal folder value by removing any final slashes
		self::$folder = rtrim($folder, '/');
	}

	static function getfolder() {
		return self::$folder;
	}

	static function view($file, $data = array()) {
		$cachedFile = self::cache($file);
		extract($data, EXTR_SKIP);
		require $cachedFile;
	}

	static function cache($file) {
		if (!file_exists(self::cachePath())) {
		  mkdir(self::cachePath(), 0744);
		}
		$cachedFile = self::cachePath() . str_replace(array('/', '.html'), array('_', ''), $file . '.php');
		if (!self::$cacheEnabled || !file_exists($cachedFile) || filemtime($cachedFile) < filemtime($file)) {
		$code = self::includeFiles($file);
		$code = self::compileCode($code);
			file_put_contents($cachedFile, '<?php class_exists(\'' . __CLASS__ . '\') or exit; ?>' . PHP_EOL . $code);
		}
		return $cachedFile;
	}

	static function clearCache() {
		foreach(glob(self::cachePath() . '*') as $file) {
			unlink($file);
		}
	}

	static function compileCode($code) {
		$code = self::compileBlock($code);
		$code = self::compileYield($code);
		$code = self::compileEscapedEchos($code);
		$code = self::compileEchos($code);
		$code = self::compilePHP($code);
		return $code;
	}

	static function includeFiles($file) {
		$code = file_get_contents(self::$folder . '/' . $file);
		preg_match_all('/{% ?(extends|include) ?\'?(.*?)\'? ?%}/i', $code, $matches, PREG_SET_ORDER);
		foreach ($matches as $value) {
			$code = str_replace($value[0], self::includeFiles($value[2]), $code);
		}
		$code = preg_replace('/{% ?(extends|include) ?\'?(.*?)\'? ?%}/i', '', $code);
		return $code;
	}

	static function compilePHP($code) {
		return preg_replace('~\{%\s*(.+?)\s*\%}~is', '<?php $1 ?>', $code);
	}

	static function compileEchos($code) {
		return preg_replace('~\{{\s*(.+?)\s*\}}~is', '<?php echo $1 ?>', $code);
	}

	static function compileEscapedEchos($code) {
		return preg_replace('~\{{{\s*(.+?)\s*\}}}~is', '<?php echo htmlentities($1 ?? "", ENT_QUOTES, \'UTF-8\') ?>', $code);
	}

	static function compileBlock($code) {
		preg_match_all('/{% ?block ?(.*?) ?%}(.*?){% ?endblock ?%}/is', $code, $matches, PREG_SET_ORDER);
		foreach ($matches as $value) {
			if (!array_key_exists($value[1], self::$blocks)) self::$blocks[$value[1]] = '';
			if (strpos($value[2], '@parent') === false) {
				self::$blocks[$value[1]] = $value[2];
			} else {
				self::$blocks[$value[1]] = str_replace('@parent', self::$blocks[$value[1]], $value[2]);
			}
			$code = str_replace($value[0], '', $code);
		}
		return $code;
	}

	static function compileYield($code) {
		foreach (self::$blocks as $block => $value) {
			$code = preg_replace('/{% ?yield ?' . $block . ' ?%}/', $value, $code);
		}
		$code = preg_replace('/{% ?yield ?(.*?) ?%}/i', '', $code);
		return $code;
	}
}