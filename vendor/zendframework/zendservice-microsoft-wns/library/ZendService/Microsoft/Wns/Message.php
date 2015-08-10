<?php

/**
 * Zend Framework (http://framework.zend.com/).
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 *
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace ZendService\Microsoft\Wns;

use ZendService\Microsoft\Exception;

/**
 * WNS Message.
 */
abstract class Message
{
    /**
     * WNS types.
     *
     * @var string
     */
    const TYPE_RAW = 'raw';
    const TYPE_TILE = 'token';
    const TYPE_TOAST = 'toast';

    /**
     * Identifier.
     *
     * @var string
     */
    protected $id;

    /**
     * WNS Token.
     *
     * @var string
     */
    protected $token;

    /**
     * Message Delay.
     *
     * @var int|null
     */
    protected $delay;

    /**
     * Get Identifier.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Identifier.
     *
     * @param string $id
     *
     * @return Message
     */
    public function setId($id)
    {
        if (!is_scalar($id)) {
            throw new Exception\InvalidArgumentException('Identifier must be a scalar value');
        }
        $this->id = $id;

        return $this;
    }

    /**
     * Get Token.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set Token.
     *
     * @param string $token
     *
     * @return Message
     */
    public function setToken($token)
    {
        if (!is_string($token)) {
            throw new Exception\InvalidArgumentException(sprintf(
                    'Device token must be a string, "%s" given.',
                    gettype($token)
            ));
        }

        if (filter_var($token, FILTER_VALIDATE_URL) === false) {
            throw new Exception\InvalidArgumentException(sprintf(
                    'Device token must be a valid URI, Token given: "%s"',
                    $token
            ));
        }

        $this->token = $token;

        return $this;
    }

    /**
     * Get Delay.
     *
     * @return int
     */
    public function getDelay()
    {
        if (!$this->delay) {
            return self::DELAY_IMMEDIATE;
        }

        return $this->delay;
    }

    /**
     * Set Delay.
     *
     * @param int $delay
     *
     * @return Toast
     */
    public function setDelay($delay)
    {
        if (!in_array($delay, array(
            static::DELAY_IMMEDIATE,
            static::DELAY_450S,
            static::DELAY_900S,
        ))) {
            throw new Exception\InvalidArgumentException('Delay must be one of the DELAY_* constants');
        }
        $this->delay = $delay;

        return $this;
    }

    /**
     * Get Notification Type.
     *
     * @return string
     */
    public static function getNotificationType()
    {
        return '';
    }

    /**
     * Get XML Payload.
     *
     * @return string
     */
    abstract public function getPayloadXml();
}
