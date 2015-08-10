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
namespace ZendService\Microsoft\Wns;

use ZendService\Microsoft\Exception;

/**
 * Windows Notification Service Response
 * This class parses out the response from
 * the Windows Notification Service API.
 *
 * @category   ZendService
 */
class Response
{
    /**
     * @var Message
     */
    protected $message;

    /**
     * @var array
     */
    protected $response;

    /**
     * Constructor.
     *
     * @param string  $response
     * @param Message $message
     *
     * @return Response
     *
     * @throws Exception\ServerUnavailable
     */
    public function __construct($response = null, Message $message = null)
    {
        if ($response) {
            $this->setResponse($response);
        }

        if ($message) {
            $this->setMessage($message);
        }
    }

    /**
     * Get Message.
     *
     * @return Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set Message.
     *
     * @param Message $message
     *
     * @return Response
     */
    public function setMessage(Message $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get Response.
     *
     * @return array
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set Response.
     *
     * @param array $response
     *
     * @return Response
     *
     * @throws Exception\InvalidArgumentException
     */
    public function setResponse(array $response)
    {
        $this->response = $response;

        return $this;
    }
}
