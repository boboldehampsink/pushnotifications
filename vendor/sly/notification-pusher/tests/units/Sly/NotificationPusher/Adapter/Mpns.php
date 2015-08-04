<?php

namespace tests\units\Sly\NotificationPusher\Adapter;

use mageekguy\atoum as Units;
use Sly\NotificationPusher\Adapter\Mpns as TestedModel;
use Sly\NotificationPusher\Model\Message as BaseMessage;
use Sly\NotificationPusher\Model\Device as BaseDevice;
use Sly\NotificationPusher\Collection\DeviceCollection as BaseDeviceCollection;
use ZendService\Microsoft\Mpns\Message\Toast as BaseServiceMessage;

/**
 * Mpns.
 *
 * @uses atoum\test
 *
 * @author Cédric Dugat <cedric@dugat.me>
 */
class Mpns extends Units\Test
{
    const MPNS_TOKEN_EXAMPLE = 'http://url.com';

    public function testConstruct()
    {
        $this
            ->when($this->mockGenerator()->orphanize('__construct'))
            ->and($this->mockClass('\Sly\NotificationPusher\Adapter\Mpns', '\Mock'))
            ->and($object = new \Mock\Mpns())
            ->array($object->getParameters())
                ->isEmpty()
        ;
    }

    public function testSupports()
    {
        $this->if($this->mockGenerator()->orphanize('__construct'))
            ->and($this->mockClass('\Sly\NotificationPusher\Adapter\Mpns', '\Mock'))
            ->and($object = new \Mock\Mpns())
            ->boolean($object->supports('*()*'))
                ->isFalse()
            ->boolean($object->supports('ABC*()*'))
                ->isFalse()
            ->boolean($object->supports(self::MPNS_TOKEN_EXAMPLE))
                ->isTrue()
        ;
    }

    public function testDefinedParameters()
    {
        $this->if($this->mockGenerator()->orphanize('__construct'))
            ->and($this->mockClass('\Sly\NotificationPusher\Adapter\Mpns', '\Mock'))
            ->and($object = new \Mock\Mpns())
            ->array($definedParameters = $object->getDefinedParameters())
            ->isEmpty()
        ;
    }

    public function testDefaultParameters()
    {
        $this->if($this->mockGenerator()->orphanize('__construct'))
            ->and($this->mockClass('\Sly\NotificationPusher\Adapter\Mpns', '\Mock'))
            ->and($object = new \Mock\Mpns())
            ->array($defaultParameters = $object->getDefaultParameters())
                ->isEmpty()
        ;
    }

    public function testRequiredParameters()
    {
        $this->if($this->mockGenerator()->orphanize('__construct'))
            ->and($this->mockClass('\Sly\NotificationPusher\Adapter\Mpns', '\Mock'))
            ->and($object = new \Mock\Mpns())
            ->array($requiredParameters = $object->getRequiredParameters())
                ->isEmpty()
        ;
    }

    /*public function testGetOpenedClient()
    {
        $this->if($this->mockGenerator()->orphanize('__construct'))
            ->and($this->mockClass('\Sly\NotificationPusher\Adapter\Mpns', '\Mock'))
            ->and($object = new \Mock\Mpns())
            ->and($this->mockGenerator()->orphanize('__construct'))
            ->and($this->mockGenerator()->orphanize('open'))
            ->and($this->mockClass('\ZendService\Microsoft\Mpns\Client', '\Mock\ZendService'))
            ->and($serviceClient = new \Mock\ZendService\Client())
            ->and($this->mockClass('\ZendService\Microsoft\Mpns\Message\Toast', '\Mock\ZendService'))
            ->and($message = new \Mock\ZendService\Toast())
            ->and($object->getMockController()->getParameters = array())

            ->when($object = new TestedModel())
            ->and($object->getOpenedClient($serviceClient, $message))
        ;
    }*/

    public function testGetServiceMessageFromOrigin()
    {
        $this->if($this->mockGenerator()->orphanize('__construct'))
            ->and($this->mockClass('\Sly\NotificationPusher\Adapter\Mpns', '\Mock'))
            ->and($object = new \Mock\Mpns())

            ->and($this->mockGenerator()->orphanize('__construct'))
            ->and($this->mockClass('\Sly\NotificationPusher\Model\Message', '\Mock'))
            ->and($message = new \Mock\Message())
            ->and($message->getMockController()->getText = 'Test')

            ->object($object->getServiceMessageFromOrigin(self::MPNS_TOKEN_EXAMPLE, $message))
                ->isInstanceOf('\ZendService\Microsoft\Mpns\Message')
        ;
    }

    public function testPush()
    {
        $this->if($this->mockGenerator()->orphanize('__construct'))
            ->and($this->mockClass('\Sly\NotificationPusher\Adapter\Mpns', '\Mock'))
            ->and($object = new \Mock\Mpns())

            ->and($this->mockClass('\ZendService\Microsoft\Mpns\Response', '\Mock\ZendService'))
            ->and($serviceResponse = new \Mock\ZendService\Response())

            ->and($this->mockGenerator()->orphanize('__construct'))
            ->and($this->mockGenerator()->orphanize('send'))
            ->and($this->mockClass('\ZendService\Microsoft\Mpns\Client', '\Mock\ZendService'))
            ->and($serviceClient = new \Mock\ZendService\Client())
            ->and($serviceClient->getMockController()->send = new $serviceResponse())

            ->and($this->mockGenerator()->orphanize('__construct'))
            ->and($this->mockClass('\Sly\NotificationPusher\Model\Push', '\Mock'))
            ->and($push = new \Mock\Push())
            ->and($message = $push->getMockController()->getMessage = new BaseMessage('Test'))
            ->and($push->getMockController()->getDevices = new BaseDeviceCollection(array(new BaseDevice(self::MPNS_TOKEN_EXAMPLE))))

            ->and($object->getMockController()->getServiceMessageFromOrigin = new BaseServiceMessage())
            ->and($object->getMockController()->getOpenedClient = $serviceClient)

            ->object($object->push($push))
                ->isInstanceOf('\Sly\NotificationPusher\Collection\DeviceCollection')
                ->hasSize(1)
        ;
    }
}
