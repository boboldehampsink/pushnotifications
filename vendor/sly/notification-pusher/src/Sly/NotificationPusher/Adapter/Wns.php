<?php

/*
 * This file is part of NotificationPusher.
 *
 * (c) 2013 Cédric Dugat <cedric@dugat.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sly\NotificationPusher\Adapter;

use Sly\NotificationPusher\Model\BaseOptionedModel;
use Sly\NotificationPusher\Model\PushInterface;
use Sly\NotificationPusher\Exception\PushException;
use Sly\NotificationPusher\Collection\DeviceCollection;
use ZendService\Microsoft\Wns\Client as ServiceClient;
use ZendService\Microsoft\Wns\Message\Toast as ServiceMessage;
use ZendService\Microsoft\Wns\Response as ServiceResponse;
use ZendService\Microsoft\Wns\Exception\RuntimeException as ServiceRuntimeException;

/**
 * WNS adapter.
 *
 * @uses \Sly\NotificationPusher\Adapter\BaseAdapter
 *
 * @author Bob Olde Hampsink <b.oldehampsink@itmundi.nl>
 */
class Wns extends BaseAdapter
{
    /** @var ServiceClient */
    private $openedClient;

    /**
     * {@inheritdoc}
     *
     * @throws \Sly\NotificationPusher\Exception\PushException
     */
    public function push(PushInterface $push)
    {
        $client = $this->getOpenedServiceClient();

        $pushedDevices = new DeviceCollection();

        foreach ($push->getDevices() as $device) {
            $message = $this->getServiceMessageFromOrigin($device->getToken(), $push->getMessage());

            try {
                $this->response = $client->send($message);
            } catch (ServiceRuntimeException $e) {
                throw new PushException($e->getMessage());
            }

            if (ServiceResponse::RESULT_OK === $this->response->getCode()) {
                $pushedDevices->add($device);
            }
        }

        return $pushedDevices;
    }

    /**
     * Get opened client.
     *
     * @param \ZendService\Microsoft\Wns\Client $client Client
     *
     * @return \ZendService\Microsoft\Wns\Client
     */
    public function getOpenedClient(ServiceClient $client)
    {
        return $client;
    }

    /**
     * Get opened ServiceClient.
     *
     * @return ServiceClient
     */
    private function getOpenedServiceClient()
    {
        if (!isset($this->openedClient)) {
            $this->openedClient = $this->getOpenedClient(new ServiceClient());
        }

        return $this->openedClient;
    }

    /**
     * Get service message from origin.
     *
     * @param \Sly\NotificationPusher\Model\DeviceInterface                    $device  Device
     * @param BaseOptionedModel|\Sly\NotificationPusher\Model\MessageInterface $message Message
     *
     * @return \ZendService\Microsoft\Wns\Message
     */
    public function getServiceMessageFromOrigin($token, BaseOptionedModel $message)
    {
        $serviceMessage = new ServiceMessage(
            $token,
            $message->getOption('title'),
            $message->getOption('body'),
            http_build_query($message->getOption('custom')),
            $message->getOption('delay')
        );
        $serviceMessage->setId(sha1($token.$message->getText()));

        return $serviceMessage;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($token)
    {
        return (bool) filter_var($token, FILTER_VALIDATE_URL);
    }

    /**
     * {@inheritdoc}
     */
    public function getDefinedParameters()
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultParameters()
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function getRequiredParameters()
    {
        return array();
    }
}
