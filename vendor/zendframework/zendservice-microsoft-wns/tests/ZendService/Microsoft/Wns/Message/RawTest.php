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
use ZendService\Microsoft\Wns\Message\Raw;

/**
 * @category   ZendService
 * @group      ZendService
 * @group      ZendService_Microsoft
 * @group      ZendService_Microsoft_Wns
 */
class RawTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->message = new Raw();
    }

    public function testSetMessageTextReturnsCorrectly()
    {
        $text = '<body>Foo wants to play Bar!</body>';
        $ret = $this->message->setBody($text);
        $this->assertInstanceOf('ZendService\Microsoft\Wns\Message', $ret);
        $checkText = $this->message;
        $this->assertInstanceOf('ZendService\Microsoft\Wns\Message\Raw', $checkText);
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
        $message = new Raw(
            'http://url.com',
            '<body>Foo wants to play Bar!</body>',
            3
        );

        $this->assertEquals('http://url.com', $message->getToken());
        $this->assertEquals('<body>Foo wants to play Bar!</body>', $message->getBody());
        $this->assertEquals(3, $message->getDelay());
    }

    public function testMessageXmlPayload()
    {
        $message = new Raw(
            'http://url.com',
            '<body>Foo wants to play Bar!</body>',
            3
        );
        $payload = $message->getPayloadXml();

        $this->assertEquals('<body>Foo wants to play Bar!</body>', $payload);
    }
}
