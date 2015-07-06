<?php

namespace Craft;

/**
 * Push Notifications - Platform Record.
 *
 * @author    Bob Olde Hampsink
 * @copyright Copyright (c) 2015, Bob Olde Hampsink
 * @license   MIT
 *
 * @link      https://github.com/boboldehampsink
 * @since     0.0.1
 */
class PushNotifications_PlatformRecord extends BaseRecord
{
    /**
     * @return string
     */
    public function getTableName()
    {
        return 'pushnotifications_platforms';
    }

    /**
     * @return array
     */
    protected function defineAttributes()
    {
        return array(
            'handle'    => array(AttributeType::Handle, 'required' => true),
            'setting'   => AttributeType::String,
        );
    }

    /**
     * @return array
     */
    public function defineRelations()
    {
        return array(
            'devices' => array(static::HAS_MANY, 'PushNotifications_DeviceRecord', 'deviceId'),
        );
    }

    /**
     * @return array
     */
    public function defineIndexes()
    {
        return array(
            array('columns' => array('handle'), 'unique' => true),
        );
    }

    /**
     * @return array
     */
    public function scopes()
    {
        return array(
            'ordered' => array('order' => 'handle'),
        );
    }
}
