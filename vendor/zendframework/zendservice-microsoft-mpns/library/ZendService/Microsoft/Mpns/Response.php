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
namespace ZendService\Microsoft\Mpns;

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
     * Response Codes.
     *
     * @var int
     */
    const RESULT_OK = 0;
    const RESULT_PROCESSING_ERROR = 1;
    const RESULT_MISSING_TOKEN = 2;
    const RESULT_MISSING_TOPIC = 3;
    const RESULT_MISSING_PAYLOAD = 4;
    const RESULT_INVALID_TOKEN_SIZE = 5;
    const RESULT_INVALID_TOPIC_SIZE = 6;
    const RESULT_INVALID_PAYLOAD_SIZE = 7;
    const RESULT_INVALID_TOKEN = 8;
    const RESULT_UNKNOWN_ERROR = 255;

    /**
     * Result Code.
     *
     * @var int
     */
    protected $code;

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
     * Get Code.
     *
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set Code.
     *
     * @param int $code
     *
     * @return Message
     */
    public function setCode($code)
    {
        if (($code < 0 || $code > 8) && $code != 255) {
            throw new Exception\InvalidArgumentException('Code must be between 0-8 OR 255');
        }
        $this->code = $code;

        return $this;
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
