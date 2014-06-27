<?php
include_once(dirname(__FILE__).'/bootstrap.php');
class PapayaModuleShortenerConnectorTest extends PapayaTestCase {
  /**
  * @covers PapayaModuleShortenerConnector::urlPattern
  */
  public function testUrlPatternSet() {
    $shortener = new PapayaModuleShortenerConnector();
    $this->assertEquals(
      'http://example.com/?shorten=%s',
      $shortener->urlPattern('http://example.com/?shorten=%s')
    );
  }

  /**
  * @covers PapayaModuleShortenerConnector::urlPattern
  */
  public function testUrlPatternGetFromModuleOptions() {
    $shortener = new PapayaModuleShortenerConnector();
    $options = array(
      '6451e8560ad3c880d9ba17075d0a408d' => array(
        'url_pattern' => 'http://example.com/?shorten=%s'
      )
    );
    $plugins = $this->getMockBuilder('PapayaPlugins')->getMock();
    $plugins->options = $options;
    $shortener->papaya($this->mockPapaya()->application(array('plugins' => $plugins)));
    $this->assertEquals('http://example.com/?shorten=%s', $shortener->urlPattern());
  }

  /**
  * @covers PapayaModuleShortenerConnector::urlPattern
  */
  public function testUrlPatternGetDefault() {
    $shortener = new PapayaModuleShortenerConnector();
    $options = array(
      '6451e8560ad3c880d9ba17075d0a408d' => array()
    );
    $plugins = $this->getMockBuilder('PapayaPlugins')->getMock();
    $plugins->options = $options;
    $shortener->papaya($this->mockPapaya()->application(array('plugins' => $plugins)));
    $this->assertEquals(
      'http://is.gd/create.php?format=simple&url=%s',
      $shortener->urlPattern()
    );
  }

  /**
  * @covers PapayaModuleShortenerConnector::getShort
  */
  public function testGetShort() {
    $shortener = new PapayaModuleShortenerConnector();
    $shortener->urlPattern(dirname(__FILE__).'/short.txt');
    $this->assertEquals(
      'http://example.com/short',
      $shortener->getShort(
        'http://this-is-a-ridiculouslay-long-hostname.example.com/and-a-quite-long-filename.html'
      )
    );
  }
}
