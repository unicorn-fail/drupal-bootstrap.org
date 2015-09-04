<?php
/**
 * @file
 * Contains Drupal\CommonMark\CommonMarkLinkRenderer.
 */

namespace Drupal\CommonMark;

use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;
use League\CommonMark\Inline\Element\AbstractInline;
use League\CommonMark\Inline\Element\Link;
use League\CommonMark\Inline\Renderer\InlineRendererInterface;

/**
 * Class CommonMarkLinkRenderer.
 *
 * @package Drupal\CommonMark
 */
class CommonMarkLinkRenderer implements InlineRendererInterface {
  private $host;

  /**
   * {@inheritdoc}
   */
  public function __construct($host = NULL) {
    $this->host = $host ? $host : parse_url($GLOBALS['base_url'], PHP_URL_HOST);
  }

  /**
   * {@inheritdoc}
   */
  public function render(AbstractInline $inline, ElementRendererInterface $html_renderer) {
    if (!($inline instanceof Link)) {
      throw new \InvalidArgumentException('Incompatible inline type: ' . get_class($inline));
    }

    $attributes = array();
    foreach ($inline->getData('attributes', []) as $key => $value) {
      $attributes[$key] = $html_renderer->escape($value, TRUE);
    }

    // Retrieve the URL.
    $url = $inline->getUrl();

    // Make external links open in a new window.
    // @todo Make this toggable via UI.
    if (variable_get('commonmark_external_urls', TRUE) && $this->isExternalUrl($url)) {
      $attributes['target'] = '_blank';
    }

    $attributes['href'] = $html_renderer->escape($url, TRUE);

    if (isset($inline->data['title'])) {
      $attributes['title'] = $html_renderer->escape($inline->data['title'], TRUE);
    }

    return new HtmlElement('a', $attributes, $html_renderer->renderInlines($inline->getChildren()));
  }

  /**
   * Determines if a URL is external to current host.
   *
   * @param string $url
   *   The URL to verify.
   *
   * @return bool
   *   TRUE or FALSE
   */
  private function isExternalUrl($url) {
    return $this->host && parse_url($url, PHP_URL_HOST) !== $this->host;
  }

}
