<?php

/**
 * Zend Framework (http://framework.zend.com/).
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 *
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 *
 * @category  ZendService
 */
namespace ZendService\Microsoft\Wns;

use ZendService\Microsoft\Exception;
use Zend\Http\Client as HttpClient;

/**
 * Windows Notification Service Client
 * This class allows the ability to send out messages
 * through the Windows Notification Service API.
 *
 * @category   ZendService
 */
class Client
{
    /**
     * @var Zend\Http\Client
     */
    protected $httpClient;

    /**
     * Get HTTP Client.
     *
     * @return Zend\Http\Client
     */
    public function getHttpClient()
    {
        if (!$this->httpClient) {
            $this->httpClient = new HttpClient();
            $this->httpClient->setOptions(array('strictredirects' => true));
        }

        return $this->httpClient;
    }

    /**
     * Set HTTP Client.
     *
     * @param Zend\Http\Client
     *
     * @return Client
     */
    public function setHttpClient(HttpClient $http)
    {
        $this->httpClient = $http;

        return $this;
    }

    /**
     * Send Message.
     *
     * @param Mesage $message
     *
     * @return Response
     *
     * @throws Exception\RuntimeException
     */
    public function send(Message $message)
    {
        $client = $this->getHttpClient();
        $client->setUri($message->getToken());
        $headers = $client->getRequest()->getHeaders();
        $headers->addHeaderLine('Context-Type', 'text/xml');
        $headers->addHeaderLine('Accept', 'application/*');
        $headers->addHeaderLine('X-NotificationClass', $message->getDelay());
        if ($message->getId()) {
            $headers->addHeaderLine('X-MessageID', $message->getId());
        }
        if ($message->getNotificationType() != Message::TYPE_RAW) {
            $headers->addHeaderLine('X-WindowsPhone-Target', $message->getNotificationType());
        }

        $response = $client->setHeaders($headers)
                           ->setMethod('POST')
                           ->setRawBody($message->getPayloadXml())
                           ->setEncType('text/xml')
                           ->send();

        switch ($response->getStatusCode()) {
            case 200:
                // check headers for response?  need to test how this actually works to correctly handle different states.
                if ($response->getHeader('NotificationStatus') == 'QueueFull') {
                    throw new Exception\RuntimeException('The devices push notification queue is full, use exponential backoff');
                }
                break;
            case 400:
                throw new Exception\RuntimeException('The authentication failed');
                break;
            case 401:
                throw new Exception\RuntimeException('The device token is not valid or there is a mismatch between certificates');
                break;
            case 404:
                throw new Exception\RuntimeException('The device subscription is invalid, stop sending notifications to this device');
                break;
            case 405:
                throw new Exception\RuntimeException('Invalid method, only POST is allowed'); // will never be hit unless overwritten
                break;
            case 406:
                throw new Exception\RuntimeException('The unauthenticated web service has reached the per-day throttling limit');
                break;
            case 412:
                throw new Exception\RuntimeException('The device is in an inactive state. You may retry once per hour');
                break;
            case 503:
                throw new Exception\RuntimeException('The server was unavailable.');
                break;
        }

        return new Response($response, $message);
    }
}
