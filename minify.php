<?php

/**
 * Minify CSS Function
 * @author    Bart van de Biezen <bart@bartvandebiezen.com>
 * @link      https://github.com/bartvandebiezen/kirby-v2-scssphp
 * @return    CSS
 * @version   0.5
 * @update    2016-06-12 by HeinerEF (changed replacement for: Remove leading zeros)
 */

function minifyCSS($buffer) {

  // Remove all CSS comments.
  $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);

  // Remove leading zeros
//$buffer = preg_replace('/(?<=[^1-9])(0+)(?=\.)/', '', $buffer); // ORG: wrong for a filename like "sourcesanspro-300.woff" in $buffer
  $buffer = preg_replace('/(?<=[^0-9])(0+)(?=\.)/', '', $buffer);

  // Remove lines and tabs.
  $buffer = preg_replace('/\n|\t|\r/', '', $buffer);

  // Remove unnecessary spaces.
  $buffer = preg_replace('/\s{2,}/', ' ', $buffer);
  $buffer = str_replace(': ', ':', $buffer);
  $buffer = str_replace('} ', '}', $buffer);
  $buffer = str_replace('{ ', '{', $buffer);
  $buffer = str_replace('; ', ';', $buffer);
  $buffer = str_replace(', ', ',', $buffer);
  $buffer = str_replace(' }', '}', $buffer);
  $buffer = str_replace(' {', '{', $buffer);
  $buffer = str_replace(' )', ')', $buffer);
  $buffer = str_replace(' (', '(', $buffer);
  $buffer = str_replace(') ', ')', $buffer);
  $buffer = str_replace('( ', '(', $buffer);
  $buffer = str_replace(' ;', ';', $buffer);
  $buffer = str_replace(' ,', ',', $buffer);

  // Fix spacing in media queries.
  $buffer = str_replace('and(', 'and (', $buffer);
  $buffer = str_replace(')and', ') and', $buffer);

  // Remove last semi-colon within a CSS rule.
  $buffer = str_replace(';}', '}', $buffer);

  return $buffer;
}


