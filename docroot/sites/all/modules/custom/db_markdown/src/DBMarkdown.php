<?php
/**
 * @file
 * Contains Drupal\DBMarkdown\DBMarkdown.
 */

namespace Drupal\DBMarkdown;

/**
 * Class DBMarkdown.
 *
 * @package Drupal\DBMarkdown
 */
class DBMarkdown extends \ParsedownExtra {

  protected $regexAttribute = '(?:[^}])';

  /**
   * {@inheritdoc}
   */
  protected function inlineLink($excerpt) {
    global $base_url;
    $link = parent::inlineLink($excerpt);

    // Make external links open in a new window.
    $url = url($link['element']['attributes']['href'], array('absolute' => TRUE));
    if (!preg_match('/^' . preg_quote($base_url, '/') . '/', $url)) {
      $link['element']['attributes']['target'] = '_blank';
    }

    return $link;
  }

  /**
   * {@inheritdoc}
   */
  protected function paragraph($line) {
    $block = parent::paragraph($line);

    // Allow paragraphs to contain attributes.
    if (preg_match('/^{(' . $this->regexAttribute . '+)}\s*/', $block['element']['text'], $matches, PREG_OFFSET_CAPTURE)) {
      $block['element']['attributes'] = $this->parseAttributeData($matches[1][0]);
      $block['element']['text'] = substr($block['element']['text'], $matches[0][1] + strlen($matches[0][0]));
    }
    return $block;
  }

  /**
   * {@inheritdoc}
   */
  protected function parseAttributeData($data) {
    $attributes = array();
    $classes = array();
    foreach (preg_split('/\s+/', $data, -1, PREG_SPLIT_NO_EMPTY) as $attribute) {
      if ($attribute[0] === '#') {
        $attributes['id'] = substr($attribute, 1);
      }
      elseif ($attribute[0] === '.') {
        $classes = array_unique(array_merge($classes, preg_split('/\./', substr($attribute, 1), -1, PREG_SPLIT_NO_EMPTY)));
      }
      elseif (strpos($attribute, '=') !== FALSE) {
        list($name, $value) = explode('=', $attribute);
        $attributes[$name] = trim($value, "'\"");
      }
    }
    if (!empty($classes)) {
      $attributes['class'] = implode(' ', $classes);
    }
    return $attributes;
  }


}
