<?php

/**
 * Zend Framework (http://framework.zend.com/).
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 *
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace ZendServiceTest\Microsoft\Wns\Message;

use ZendService\Microsoft\Wns\Message;
use ZendService\Microsoft\Wns\Message\Tile;

/**
 * @category   ZendService
 * @group      ZendService
 * @group      ZendService_Microsoft
 * @group      ZendService_Microsoft_Wns
 */
class TileTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->message = new Tile();
    }

    public function testSetMessageTextReturnsCorrectly()
    {
        $text = 'Foo wants to play Bar!';
        $ret = $this->message->setTitle($text);
        $this->assertInstanceOf('ZendService\Microsoft\Wns\Message', $ret);
        $checkText = $this->message;
        $this->assertInstanceOf('ZendService\Microsoft\Wns\Message\Tile', $checkText);
        $this->assertEquals($text, $checkText->getTitle());
    }

    public function testSetMessageThrowsExceptionOnTextNonString()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->message->setTitle(array());
    }

    public function testSetMessageThrowsExceptionOnDelayNonString()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->message->setDelay(array());
    }

    public function testMessageConstructor()
    {
        $message = new Tile(
            'http://url.com',
            'Background Image',
            1,
            'Title',
            'Back Background Image',
            'Back Title',
            'Back Content',
            'Tile ID',
            1
        );

        $this->assertEquals('http://url.com', $message->getToken());
        $this->assertEquals('Background Image', $message->getBackgroundImage());
        $this->assertEquals(1, $message->getCount());
        $this->assertEquals('Title', $message->getTitle());
        $this->assertEquals('Back Background Image', $message->getBackBackgroundImage());
        $this->assertEquals('Back Title', $message->getBackTitle());
        $this->assertEquals('Back Content', $message->getBackContent());
        $this->assertEquals('Tile ID', $message->getTileId());
        $this->assertEquals(1, $message->getDelay());
    }

    public function testMessageXmlPayload()
    {
        $message = new Tile(
            'http://url.com',
            'Background Image',
            1,
            'Title',
            'Back Background Image',
            'Back Title',
            'Back Content',
            'Tile ID',
            1
        );
        $payload = $message->getPayloadXml();

        $xml = '<?xml version="1.0" encoding="utf-8"?>'
            .'<wp:Notification xmlns:wp="WPNotification">'
            .'<wp:Tile Id="Tile ID">'
            .'<wp:BackgroundImage>Background Image</wp:BackgroundImage>'
            .'<wp:Count>1</wp:Count>'
            .'<wp:Title>Title</wp:Title>'
            .'<wp:BackBackgroundImage>Back Background Image</wp:BackBackgroundImage>'
            .'<wp:BackTitle>Back Title</wp:BackTitle>'
            .'<wp:BackContent>Back Content</wp:BackContent>'
            .'</wp:Tile>'
            .'</wp:Notification>';

        $this->assertEquals($xml, $payload);
    }
}
