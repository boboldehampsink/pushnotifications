<?php

namespace tests\units\Sly\NotificationPusher\Adapter;

use mageekguy\atoum as Units;
use Sly\NotificationPusher\Adapter\Wns as TestedModel;
use Sly\NotificationPusher\Model\Message as BaseMessage;
use Sly\NotificationPusher\Model\Device as BaseDevice;
use Sly\NotificationPusher\Collection\DeviceCollection as BaseDeviceCollection;
use ZendService\Microsoft\Wns\Message\Toast as BaseServiceMessage;

/**
 * Wns.
 *
 * @uses atoum\test
 *
 * @author Cédric Dugat <cedric@dugat.me>
 */
class Wns extends Units\Test
{
    const WNS_TOKEN_EXAMPLE = 'http://url.com';

    public function testConstruct()
    {
        $this
            ->when($this->mockGenerator()->orphanize('__construct'))
            ->and($this->mockClass('\Sly\NotificationPusher\Adapter\Wns', '\Mock'))
            ->and($object = new \Mock\Wns())
            ->array($object->getParameters())
                ->isEmpty()
        ;
    }

    public function testSupports()
    {
        $this->if($this->mockGenerator()->orphanize('__construct'))
            ->and($this->mockClass('\Sly\NotificationPusher\Adapter\Wns', '\Mock'))
            ->and($object = new \Mock\Wns())
            ->boolean($object->supports('*()*'))
                ->isFalse()
            ->boolean($object->supports('ABC*()*'))
                ->isFalse()
            ->boolean($object->supports(self::WNS_TOKEN_EXAMPLE))
                ->isTrue()
        ;
    }

    public function testDefinedParameters()
    {
        $this->if($this->mockGenerator()->orphanize('__construct'))
            ->and($this->mockClass('\Sly\NotificationPusher\Adapter\Wns', '\Mock'))
            ->and($object = new \Mock\Wns())
            ->array($definedParameters = $object->getDefinedParameters())
            ->isEmpty()
        ;
    }

    public function testDefaultParameters()
    {
        $this->if($this->mockGenerator()->orphanize('__construct'))
            ->and($this->mockClass('\Sly\NotificationPusher\Adapter\Wns', '\Mock'))
            ->and($object = new \Mock\Wns())
            ->array($defaultParameters = $object->getDefaultParameters())
                ->isEmpty()
        ;
    }

    public function testRequiredParameters()
    {
        $this->if($this->mockGenerator()->orphanize('__construct'))
            ->and($this->mockClass('\Sly\NotificationPusher\Adapter\Wns', '\Mock'))
            ->and($object = new \Mock\Wns())
            ->array($requiredParameters = $object->getRequiredParameters())
                ->isEmpty()
        ;
    }

    /*public function testGetOpenedClient()
    {
        $this->if($this->mockGenerator()->orphanize('__construct'))
            ->and($this->mockClass('\Sly\NotificationPusher\Adapter\Wns', '\Mock'))
            ->and($object = new \Mock\Wns())
            ->and($this->mockGenerator()->orphanize('__construct'))
            ->and($this->mockGenerator()->orphanize('open'))
            ->and($this->mockClass('\ZendService\Microsoft\Wns\Client', '\Mock\ZendService'))
            ->and($serviceClient = new \Mock\ZendService\Client())
            ->and($this->mockClass('\ZendService\Microsoft\Wns\Message\Toast', '\Mock\ZendService'))
            ->and($message = new \Mock\ZendService\Toast())
            ->and($object->getMockController()->getParameters = array())

            ->when($object = new TestedModel())
            ->and($object->getOpenedClient($serviceClient, $message))
        ;
    }*/

    public function testGetServiceMessageFromOrigin()
    {
        $this->if($this->mockGenerator()->orphanize('__construct'))
            ->and($this->mockClass('\Sly\NotificationPusher\Adapter\Wns', '\Mock'))
            ->and($object = new \Mock\Wns())

            ->and($this->mockGenerator()->orphanize('__construct'))
            ->and($this->mockClass('\Sly\NotificationPusher\Model\Message', '\Mock'))
            ->and($message = new \Mock\Message())
            ->and($message->getMockController()->getText = 'Test')

            ->object($object->getServiceMessageFromOrigin(self::WNS_TOKEN_EXAMPLE, $message))
                ->isInstanceOf('\ZendService\Microsoft\Wns\Message')
        ;
    }

    public function testPush()
    {
        $this->if($this->mockGenerator()->orphanize('__construct'))
            ->and($this->mockClass('\Sly\NotificationPusher\Adapter\Wns', '\Mock'))
            ->and($object = new \Mock\Wns())

            ->and($this->mockClass('\ZendService\Microsoft\Wns\Response', '\Mock\ZendService'))
            ->and($serviceResponse = new \Mock\ZendService\Response())

            ->and($this->mockGenerator()->orphanize('__construct'))
            ->and($this->mockGenerator()->orphanize('send'))
            ->and($this->mockClass('\ZendService\Microsoft\Wns\Client', '\Mock\ZendService'))
            ->and($serviceClient = new \Mock\ZendService\Client())
            ->and($serviceClient->getMockController()->send = new $serviceResponse())

            ->and($this->mockGenerator()->orphanize('__construct'))
            ->and($this->mockClass('\Sly\NotificationPusher\Model\Push', '\Mock'))
            ->and($push = new \Mock\Push())
            ->and($message = $push->getMockController()->getMessage = new BaseMessage('Test'))
            ->and($push->getMockController()->getDevices = new BaseDeviceCollection(array(new BaseDevice(self::WNS_TOKEN_EXAMPLE))))

            ->and($object->getMockController()->getServiceMessageFromOrigin = new BaseServiceMessage())
            ->and($object->getMockController()->getOpenedClient = $serviceClient)

            ->object($object->push($push))
                ->isInstanceOf('\Sly\NotificationPusher\Collection\DeviceCollection')
                ->hasSize(1)
        ;
    }
}
