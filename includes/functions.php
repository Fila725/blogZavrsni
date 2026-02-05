<?php
declare(strict_types=1);

function e(string $s): string {
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function slugify(string $text): string {
  $text = strtolower(trim($text));
  $text = preg_replace('/[^a-z0-9]+/i', '-', $text);
  $text = trim($text, '-');
  return $text ?: 'post';
}
