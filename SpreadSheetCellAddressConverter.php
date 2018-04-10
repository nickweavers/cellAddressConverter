<?php
/**
* In spreadsheets, the cell address notation that is generally used is called the A1 notation. 
* Column coordinates are specified in base26 as characters from A through Z with the first column being A, 26th column being Z, 27 column being AA etc.
* Row coordinates are in the standard base 10. 
* 
* This class will convert A1 to R1C1 coordinates for a single coordinate via the A1toR1C1 method. 
*/
class cellAddressConverter {

    /**
    * This method will convert A1 to R1C1 coordinates for a single coordinate. 
    * The R1C1 is returned as an array with elements row and column.
    *     
    * @param string $cell_address
    */
    public static function A1toR1C1($cell_address) {
        $pattern = '/^(?<letters>[a-zA-Z]+)(?<number>[1-9]+)$/i';
        $rc = preg_match($pattern, $cell_address, $matches);
        if ($rc) {
            $col = self::alphaToInt26($matches['letters']);
            $row = $matches['number'];
            return array($row,$col);               
        } else {
            return false;
        }        
    }
    /**
    * This method will convert an R1C1 coordinate pair into and A1 coordinate as a string.
    * 
    * @param array $r1c1 
    */
    public static function R1C1toA1($r1c1) {
        if (gettype($r1c1)=='array') {
            $row = $r1c1[0];        
            $col = self::int26ToAlpha($r1c1[1]);
            return $col . $row;               
        } else {
            return false;
        }        
    }
    /**
    * This method will convert a alpha column string into a base 26 numerical representation 
    *     
    * @param string $col
    */
    public static function alphaToInt26(string $col) {
        $alpha = strtoupper($col);
        $num_letters = strlen($alpha);
        $j = 0;
        for($i = $num_letters-1; $i >= 0; $i--) {
            $letter_cursor = $alpha[$i];
            $number = ord($letter_cursor) - 64;
            $acc_number = $acc_number + pow(26,$j) * $number;
            $j++;       
        }
        return $acc_number;        
    }
    /**
    * This method will convert a base 26 numerical representation into an alpha based string
    * 
    * @param integer $num
    */
    public static function int26ToAlpha($num)
    {
        do {
            $val = ($num % 26) ?: 26;
            $num = ($num - $val) / 26;
            $b26 = chr($val + 64).($b26 ?: '');
        } while (0 < $num);
        return $b26;
    }    
          
}
/*
// testing
$num = cellAddressConverter::alphaToInt26('A');
$str = cellAddressConverter::int26ToAlpha($num);

$num = cellAddressConverter::alphaToInt26('Y');
$str = cellAddressConverter::int26ToAlpha($num);
 
$num = cellAddressConverter::alphaToInt26('AA'); 
$str = cellAddressConverter::int26ToAlpha($num);

$num = cellAddressConverter::alphaToInt26('ABC');
$str = cellAddressConverter::int26ToAlpha($num);

$num = cellAddressConverter::alphaToInt26('abcdefg');
$str = cellAddressConverter::int26ToAlpha($num);

$num = cellAddressConverter::alphaToInt26('abcdefghijklmnopq'); // this causes a floating point number which breaks int26ToAlpha
$str = cellAddressConverter::int26ToAlpha($num);

$r1c1 = cellAddressConverter::A1toR1C1(B5);
$str  = cellAddressConverter::R1C1toA1($r1c1);

$r1c1 = cellAddressConverter::A1toR1C1(AB23);
$str  = cellAddressConverter::R1C1toA1($r1c1);

exit;
*/  
?>
