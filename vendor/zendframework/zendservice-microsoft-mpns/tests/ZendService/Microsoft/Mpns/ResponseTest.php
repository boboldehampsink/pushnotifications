<?php

/**
 * Zend Framework (http://framework.zend.com/).
 *
 * @link       http://github.com/zendframework/zf2 for the canonical source repository
 *
 * @copyright  Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd New BSD License
 *
 * @category   ZendService
 */
namespace ZendServiceTest\Microsoft\Mpns;

use ZendService\Microsoft\Mpns\Message\Toast;
use ZendService\Microsoft\Mpns\Response;

/**
 * @category   ZendService
 * @group      ZendService
 * @group      ZendService_Microsoft
 * @group      ZendService_Microsoft_Mpns
 */
class ResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorExpectedBehavior()
    {
        $response = new Response();
        $this->assertNull($response->getResponse());
        $this->assertNull($response->getMessage());

        $message = new Toast();
        $response = new Response(null, $message);
        $this->assertEquals($message, $response->getMessage());
        $this->assertNull($response->getResponse());
    }

    public function testInvalidConstructorThrowsException()
    {
        $this->setExpectedException('PHPUnit_Framework_Error');
        $response = new Response('{bad');
    }

    public function testMessageExpectedBehavior()
    {
        $message = new Toast();
        $response = new Response();
        $response->setMessage($message);
        $this->assertEquals($message, $response->getMessage());
    }
}
