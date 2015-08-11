<?php

/**
 * Zend Framework (http://framework.zend.com/).
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 *
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace ZendService\Microsoft\Wns\Message;

use ZendService\Microsoft\Wns\Message;
use ZendService\Microsoft\Exception;

/**
 * Message Toast Object.
 */
class Toast extends Message
{
    /**
     * Wns delays.
     *
     * @var int
     */
    const DELAY_IMMEDIATE = 2;
    const DELAY_450S = 12;
    const DELAY_900S = 22;

    /**
     * Message Title.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Message Body.
     *
     * @var string|null
     */
    protected $body;

    /**
     * Message Params.
     *
     * @var string|null
     */
    protected $params;

    /**
     * Constructor.
     *
     * @param string $token
     * @param string $title
     * @param string $body
     * @param string $params
     * @param int    $delay
     *
     * @return Toast
     */
    public function __construct($token = null, $title = null, $body = null, $params = null, $delay = null)
    {
        if ($token !== null) {
            $this->setToken($token);
        }
        if ($title !== null) {
            $this->setTitle($title);
        }
        if ($body !== null) {
            $this->setBody($body);
        }
        if ($params !== null) {
            $this->setParams($params);
        }
        if ($delay !== null) {
            $this->setDelay($delay);
        }
    }

    /**
     * Get Title.
     *
     * @return string|null
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set Title.
     *
     * @param string|null $title
     *
     * @return Toast
     */
    public function setTitle($title)
    {
        if (!is_null($title) && !is_scalar($title)) {
            throw new Exception\InvalidArgumentException('Title must be null OR a scalar value');
        }
        $this->title = $title;

        return $this;
    }

    /**
     * Get Body.
     *
     * @return string|null
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set Body.
     *
     * @param string|null $body
     *
     * @return Alert
     */
    public function setBody($body)
    {
        if (!is_null($body) && !is_scalar($body)) {
            throw new Exception\InvalidArgumentException('Body must be null OR a scalar value');
        }
        $this->body = $body;

        return $this;
    }

    /**
     * Get Params.
     *
     * @return string|null
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Set Params.
     *
     * @param string|null $params
     *
     * @return Toast
     */
    public function setParams($params)
    {
        if (!is_null($params) && !is_scalar($params)) {
            throw new Exception\InvalidArgumentException('Params must be null OR a scalar value');
        }
        $this->params = $params;

        return $this;
    }

    /**
     * Get Notification Type.
     *
     * @return string
     */
    public static function getNotificationType()
    {
        return 'toast';
    }

    /**
     * To Payload
     * Formats a WPS toast.
     *
     * @return array|string
     */
    public function getPayloadXml()
    {
        $params = $this->getParams();
        $ret = '<?xml version="1.0" encoding="utf-8"?>';
        if (!empty($params)) {
            $ret .= '<toast launch="'.htmlspecialchars($this->getParams()).'">';
        } else {
            $ret .= '<toast>';
        }
        $ret .= '<visual>'
            .'<binding template="ToastText02">'
            .'<text id="1">'.htmlspecialchars($this->getTitle()).'</text>'
            .'<text id="2">'.htmlspecialchars($this->getBody()).'</text>'
            .'</binding>'
            .'</visual>'
            .'</toast>';

        return $ret;
    }
}
