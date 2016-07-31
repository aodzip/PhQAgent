<?php
namespace utils;
abstract class TextFormat {

	CONST WHITE        = "\x1b[38;5;231m";
	CONST BLACK        = "\x1b[38;5;16m";
	//CONST CYAN       = "\x1b[0;36m";
	CONST GREEN        = "\x1b[38;5;83m";
	CONST DARK_BLUE    = "\x1b[38;5;19m";
	CONST YELLOW       = "\x1b[38;5;227m";
	CONST RED          = "\x1b[38;5;203m";
	CONST GOLD         = "\x1b[38;5;214m";
	CONST AQUA         = "\x1b[38;5;87m";
	CONST PURPLE       = "\x1b[38;5;127m";
	CONST LIGHT_PURPLE = "\x1b[38;5;207m";
	CONST RESET        = "\x1b[0m";
	CONST BOLD         = "\x1b[1m";
	CONST ITALIC       = "\x1b[3m";
	CONST UNDERLINE    = "\x1b[4m";

	public static function clean($string) {
		return \preg_replace("/\x1b[\\(\\][[0-9;\\[\\(]+[Bm]/", "", $string);
		//官方正则 "/\x1b[\\(\\][[0-9;\\[\\(]+[Bm]/"
		//自用   '/\\x1b\[[01][;]?[0-9]?[0-9]?m/'
	}

}
