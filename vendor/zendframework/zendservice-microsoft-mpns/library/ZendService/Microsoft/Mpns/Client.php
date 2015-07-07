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
namespace ZendService\Microsoft\Mpns;

use ZendService\Microsoft\Exception;
use Zend\Http\Client as HttpClient;
use Zend\Json\Json;

/**
 * Microsoft MPNS Client
 * This class allows the ability to send out messages
 * through the Microsoft MPNS API.
 *
 * @category   ZendService
 */
class Client
{
    /**
     * @const string Server URI
     */
    const SERVER_URI = 'http://sn1.notify.live.net/throttledthirdparty/01.00/';

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
        $client->setUri(self::SERVER_URI);
        $headers = $client->getRequest()->getHeaders();

        $response = $client->setHeaders($headers)
                           ->setMethod('POST')
                           ->setRawBody($message->toJson())
                           ->setEncType('application/json')
                           ->send();

        switch ($response->getStatusCode()) {
            case 500:
                throw new Exception\RuntimeException('500 Internal Server Error');
                break;
            case 503:
                $exceptionMessage = '503 Server Unavailable';
                if ($retry = $response->getHeaders()->get('Retry-After')) {
                    $exceptionMessage .= '; Retry After: '.$retry;
                }
                throw new Exception\RuntimeException($exceptionMessage);
                break;
            case 401:
                throw new Exception\RuntimeException('401 Forbidden; Authentication Error');
                break;
            case 400:
                throw new Exception\RuntimeException('400 Bad Request; invalid message');
                break;
        }

        if (!$response = Json::decode($response->getBody(), Json::TYPE_ARRAY)) {
            throw new Exception\RuntimeException('Response body did not contain a valid JSON response');
        }

        return new Response($response, $message);
    }
}
