<?php

function reverseOrderOfWords(string $str): string {

  //Q3.  Write a function which takes a string and reverses the order of words in that string.
  // (e.g. "Hello world!" becomes "world! Hello")

  $array_reverse  =  ( array_reverse( explode(" ", $str) ) );
  $implode_words  =  implode(' ', $array_reverse);
  return $implode_words;

}


function spinWords(string $str): string {

//  Q2. Write a function which takes a string and reverses each word in that string.
// (e.g. "Hello world!" becomes "olleH !dlrow")

$words  = explode(" ", $str);
$newStr = [];

foreach( $words as $key => $val) {

  $newStr[] .=  strrev($words[$key]);

}

return implode(" ", $newStr);

}

function reverseString(string $str): string {

// Q1. Write a function which takes a string and reverses that string.
// (e.g. "Hello world! " becomes "!dlrow olleH")

return spinWords(reverseOrderOfWords($str));

}



function findMaxNumber($num) {

  //Q4. Write a function which takes an array of numbers and returns the maximum element
  // of that array.
  return max($num);

}

/* Check the correctness of each functions */

$element = ([1562, 1, -44, -2, 222, 929,]);
print_r(findMaxNumber($element));
// print_r(reverseString("Hello world!"));

?>