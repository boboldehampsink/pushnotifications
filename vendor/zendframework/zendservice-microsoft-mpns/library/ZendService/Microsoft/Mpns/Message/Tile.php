<?php

/**
 * Zend Framework (http://framework.zend.com/).
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 *
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace ZendService\Microsoft\Mpns\Message;

use ZendService\Microsoft\Mpns\Message;
use ZendService\Microsoft\Exception;

/**
 * Message Tile Object.
 */
class Tile extends Message
{
    /**
     * Mpns delays.
     *
     * @var int
     */
    const DELAY_IMMEDIATE = 1;
    const DELAY_450S = 11;
    const DELAY_900S = 21;

    /**
     * Message Background Image.
     *
     * @var string|null
     */
    protected $backgroundImage;

    /**
     * Message Count.
     *
     * @var int
     */
    protected $count = 0;

    /**
     * Message Title.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Message Back Background Image.
     *
     * @var string|null
     */
    protected $backBackgroundImage;

    /**
     * Message Back Title.
     *
     * @var string|null
     */
    protected $backTitle;

    /**
     * Message Back Content.
     *
     * @var string|null
     */
    protected $backContent;

    /**
     * Message Tile ID.
     *
     * @var string|null
     */
    protected $tileId;

    /**
     * Constructor.
     *
     * @param string $token
     * @param string $backgroundImage
     * @param int    $count
     * @param string $title
     * @param string $backBackgroundImage
     * @param string $backTitle
     * @param string $backContent
     * @param string $tileId
     * @param int    $delay
     *
     * @return Toast
     */
    public function __construct($token = null, $backgroundImage = null, $count = 0, $title = null, $backBackgroundImage = null, $backTitle = null, $backContent = null, $tileId = null, $delay = null)
    {
        if ($token !== null) {
            $this->setToken($token);
        }
        if ($backgroundImage !== null) {
            $this->setBackgroundImage($backgroundImage);
        }
        if ($count) {
            $this->setCount($count);
        }
        if ($title !== null) {
            $this->setTitle($title);
        }
        if ($backBackgroundImage !== null) {
            $this->setBackBackgroundImage($backBackgroundImage);
        }
        if ($backTitle !== null) {
            $this->setBackTitle($backTitle);
        }
        if ($backContent !== null) {
            $this->setBackContent($backContent);
        }
        if ($tileId !== null) {
            $this->setTileId($tileId);
        }
        if ($delay !== null) {
            $this->setDelay($delay);
        }
    }

    /**
     * Get Background Image.
     *
     * @return string|null
     */
    public function getBackgroundImage()
    {
        return $this->backgroundImage;
    }

    /**
     * Set Background Image.
     *
     * @param string|null $backgroundImage
     *
     * @return Tile
     */
    public function setBackgroundImage($backgroundImage)
    {
        if (!is_null($backgroundImage) && !is_scalar($backgroundImage)) {
            throw new Exception\InvalidArgumentException('Background Image must be null OR a scalar value');
        }
        $this->backgroundImage = $backgroundImage;

        return $this;
    }

    /**
     * Get Count.
     *
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set Count.
     *
     * @param int $count
     *
     * @return Tile
     */
    public function setCount($count)
    {
        if (!is_numeric($count)) {
            throw new Exception\InvalidArgumentException('Count must be numeric value');
        }
        $this->count = $count;

        return $this;
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
     * @return Tile
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
     * Get Back Background Image.
     *
     * @return string|null
     */
    public function getBackBackgroundImage()
    {
        return $this->backBackgroundImage;
    }

    /**
     * Set Back Background Image.
     *
     * @param string|null $backBackgroundImage
     *
     * @return Tile
     */
    public function setBackBackgroundImage($backBackgroundImage)
    {
        if (!is_null($backBackgroundImage) && !is_scalar($backBackgroundImage)) {
            throw new Exception\InvalidArgumentException('Back Background Image must be null OR a scalar value');
        }
        $this->backBackgroundImage = $backBackgroundImage;

        return $this;
    }

    /**
     * Get Back Title.
     *
     * @return string|null
     */
    public function getBackTitle()
    {
        return $this->backTitle;
    }

    /**
     * Set Back Title.
     *
     * @param string|null $backTitle
     *
     * @return Tile
     */
    public function setBackTitle($backTitle)
    {
        if (!is_null($backTitle) && !is_scalar($backTitle)) {
            throw new Exception\InvalidArgumentException('Back Title must be null OR a scalar value');
        }
        $this->backTitle = $backTitle;

        return $this;
    }

    /**
     * Get Back Content.
     *
     * @return string|null
     */
    public function getBackContent()
    {
        return $this->backContent;
    }

    /**
     * Set Back Content.
     *
     * @param string|null $backContent
     *
     * @return Tile
     */
    public function setBackContent($backContent)
    {
        if (!is_null($backContent) && !is_scalar($backContent)) {
            throw new Exception\InvalidArgumentException('Back Content must be null OR a scalar value');
        }
        $this->backContent = $backContent;

        return $this;
    }

    /**
     * Get Tile ID.
     *
     * @return string|null
     */
    public function getTileId()
    {
        return $this->tileId;
    }

    /**
     * Set Tile ID.
     *
     * @param string|null $tileId
     *
     * @return Tile
     */
    public function setTileId($tileId)
    {
        if (!is_null($tileId) && !is_scalar($tileId)) {
            throw new Exception\InvalidArgumentException('Tile ID must be null OR a scalar value');
        }
        $this->tileId = $tileId;

        return $this;
    }

    /**
     * Get Notification Type.
     *
     * @return string
     */
    public static function getNotificationType()
    {
        return 'token';
    }

    /**
     * To Payload
     * Formats a WPS tile.
     *
     * @return array|string
     */
    public function getPayloadXml()
    {
        $ret = '<?xml version="1.0" encoding="utf-8"?>'
            .'<wp:Notification xmlns:wp="WPNotification">'
            .'<wp:Tile'.(($this->getTileId()) ? ' Id="'.htmlspecialchars($this->getTileId()).'"' : '').'>'
            .'<wp:BackgroundImage>'.htmlspecialchars($this->getBackgroundImage()).'</wp:BackgroundImage>'
            .'<wp:Count>'.(int) $this->getCount().'</wp:Count>'
            .'<wp:Title>'.htmlspecialchars($this->getTitle()).'</wp:Title>';
        if ($this->getBackBackgroundImage()) {
            $ret .= '<wp:BackBackgroundImage>'.htmlspecialchars($this->getBackBackgroundImage()).'</wp:BackBackgroundImage>';
        }
        if ($this->getBackTitle()) {
            $ret .= '<wp:BackTitle>'.htmlspecialchars($this->getBackTitle()).'</wp:BackTitle>';
        }
        if ($this->getBackContent()) {
            $ret .= '<wp:BackContent>'.htmlspecialchars($this->getBackContent()).'</wp:BackContent>';
        }
        $ret .= '</wp:Tile>'
            .'</wp:Notification>';

        return $ret;
    }
}
