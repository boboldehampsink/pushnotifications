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
use ZendService\Microsoft\Wns\Message\Toast;

/**
 * @category   ZendService
 * @group      ZendService
 * @group      ZendService_Microsoft
 * @group      ZendService_Microsoft_Wns
 */
class ToastTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->message = new Toast();
    }

    public function testSetMessageTextReturnsCorrectly()
    {
        $text = 'Foo wants to play Bar!';
        $ret = $this->message->setBody($text);
        $this->assertInstanceOf('ZendService\Microsoft\Wns\Message', $ret);
        $checkText = $this->message;
        $this->assertInstanceOf('ZendService\Microsoft\Wns\Message\Toast', $checkText);
        $this->assertEquals($text, $checkText->getBody());
    }

    public function testSetMessageThrowsExceptionOnTextNonString()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->message->setBody(array());
    }

    public function testSetMessageThrowsExceptionOnDelayNonString()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->message->setDelay(array());
    }

    public function testMessageConstructor()
    {
        $message = new Toast(
            'http://url.com',
            'Title',
            'Body',
            'Params',
            2
        );

        $this->assertEquals('http://url.com', $message->getToken());
        $this->assertEquals('Title', $message->getTitle());
        $this->assertEquals('Body', $message->getBody());
        $this->assertEquals('Params', $message->getParams());
        $this->assertEquals(2, $message->getDelay());
    }

    public function testMessageXmlPayload()
    {
        $message = new Toast(
            'http://url.com',
            'Title',
            'Body',
            'Params',
            2
        );
        $payload = $message->getPayloadXml();

        $xml = '<?xml version="1.0" encoding="utf-8"?>'
            .'<wp:Notification xmlns:wp="WPNotification">'
            .'<wp:Toast>'
            .'<wp:Text1>Title</wp:Text1>'
            .'<wp:Text2>Body</wp:Text2>'
            .'<wp:Param>Params</wp:Param>'
            .'</wp:Toast>'
            .'</wp:Notification>';

        $this->assertEquals($xml, $payload);
    }
}
