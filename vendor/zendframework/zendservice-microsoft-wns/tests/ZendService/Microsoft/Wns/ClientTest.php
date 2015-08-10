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
namespace ZendServiceTest\Microsoft\Wns;

use Zend\Http\Client\Adapter\Test;
use Zend\Http\Client as HttpClient;
use ZendService\Microsoft\Wns\Client;

/**
 * @category   ZendService
 * @group      ZendService
 * @group      ZendService_Google
 * @group      ZendService_Google_Gcm
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{
    protected $httpAdapter;
    protected $httpClient;
    protected $wnsClient;

    public function setUp()
    {
        $this->httpClient = new HttpClient();
        $this->httpAdapter = new Test();
        $this->httpClient->setAdapter($this->httpAdapter);
        $this->wnsClient = new Client();
        $this->wnsClient->setHttpClient($this->httpClient);
    }

    public function testGetHttpClientReturnsDefault()
    {
        $wns = new Client();
        $this->assertEquals('Zend\Http\Client', get_class($wns->getHttpClient()));
        $this->assertTrue($wns->getHttpClient() instanceof HttpClient);
    }

    public function testSetHttpClient()
    {
        $client = new HttpClient();
        $this->wnsClient->setHttpClient($client);
        $this->assertEquals($client, $this->wnsClient->getHttpClient());
    }
}
