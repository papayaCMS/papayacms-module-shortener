<?php
/**
* URL Shortener connector
*
* @copyright by dimensional Software GmbH, Cologne, Germany - All rights reserved.
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, version 2
*
* You can redistribute and/or modify this script under the terms of the GNU General Public
* License (GPL) version 2, provided that the copyright and license notes, including these
* lines, remain unmodified. papaya is distributed in the hope that it will be useful, but
* WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
* FOR A PARTICULAR PURPOSE.
*
* @package Papaya-Modules
* @subpackage Free-Shortener
* @version $Id: Connector.php 39862 2014-06-27 09:40:22Z kersken $
*/

/**
* URL Shortener connector class
*
* Usage:
* $connector = $this->papaya()->plugins->get('6451e8560ad3c880d9ba17075d0a408d');
* $urlPattern = $connector->urlPattern($pattern = NULL);
* $shortLink = $connector->getShort($link);
*
* @package Papaya-Modules
* @subpackage Free-Shortener
*/
class PapayaModuleShortenerConnector extends base_connector {
  /**
  * URL pattern for shortening service
  * @var string
  */
  private $_urlPattern = NULL;

  public $pluginOptionFields = array(
    'url_pattern' => array(
      'URL pattern',
      'isSomeText',
      TRUE,
      'input',
      255,
      '',
      'http://is.gd/create.php?format=simple&url=%s'
    )
  );

  /**
  * Set/get the URL pattern
  *
  * @param string $pattern optional, default NULL
  * @return string
  */
  public function urlPattern($pattern = NULL) {
    if ($pattern !== NULL) {
      $this->_urlPattern = $pattern;
    } elseif ($this->_urlPattern === NULL) {
      $options = $this->papaya()->plugins->options['6451e8560ad3c880d9ba17075d0a408d'];
      if (isset($options['url_pattern']) && !empty($options['url_pattern'])) {
        $this->_urlPattern = $options['url_pattern'];
      } else {
        $this->_urlPattern = 'http://is.gd/create.php?format=simple&url=%s';
      }
    }
    return $this->_urlPattern;
  }

  /**
  * Get a shorter link
  *
  * @param string $link
  * @return string The shortened link
  */
  public function getShort($link) {
    $serviceUrl = sprintf(
      $this->urlPattern(),
      rawurlencode($link)
    );
    $streamOptions = array(
      'method' => 'GET'
    );
    $context = stream_context_create(
      array('http' => $streamOptions)
    );
    if ($stream = fopen($serviceUrl, 'r', FALSE, $context)) {
      $link = stream_get_contents($stream);
      fclose($stream);
    }
    return trim($link);
  }
}