<?php

function slug($string) {
    $string = preg_replace('![^' . preg_quote('-') . '\pL\pN\s]+!u', '', mb_strtolower($string));
    $string = preg_replace('![' . preg_quote('-') . '\s]+!u', '-', $string);
    
    $string = translitirate($string);
    return trim($string, '-');
}

function translitirate($string) {
    $converter = [
        "а" => "a",
		"ый" => "iy",
		"ые" => "ie",
		"б" => "b",
		"в" => "v",
		"г" => "g",
		"д" => "d",
		"е" => "e",
		"ё" => "yo",
		"ж" => "zh",
		"з" => "z",
		"и" => "i",
		"й" => "y",
		"к" => "k",
		"л" => "l",
		"м" => "m",
		"н" => "n",
		"о" => "o",
		"п" => "p",
		"р" => "r",
		"с" => "s",
		"т" => "t",
		"у" => "u",
		"ф" => "f",
		"х" => "kh",
		"ц" => "ts",
		"ч" => "ch",
		"ш" => "sh",
		"щ" => "shch",
		"ь" => "",
		"ы" => "y",
		"ъ" => "",
		"э" => "e",
		"ю" => "yu",
		"я" => "ya",
		"йо" => "yo",
		"ї" => "yi",
		"і" => "i",
		"є" => "ye",
		"ґ" => "g"
    ];


    return strtr($string, $converter);
}