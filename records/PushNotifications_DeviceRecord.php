<?php

namespace Craft;

/**
 * Push Notifications - Device Record.
 *
 * @author    Bob Olde Hampsink
 * @copyright Copyright (c) 2015, Bob Olde Hampsink
 * @license   MIT
 *
 * @link      https://github.com/boboldehampsink
 * @since     0.0.1
 */
class PushNotifications_DeviceRecord extends BaseRecord
{
    /**
     * @return string
     */
    public function getTableName()
    {
        return 'pushnotifications_devices';
    }

    /**
     * @return array
     */
    protected function defineAttributes()
    {
        return array(
            'token' => array(AttributeType::String, 'required' => true),
        );
    }

    /**
     * @return array
     */
    public function defineRelations()
    {
        return array(
            'element'  => array(static::BELONGS_TO, 'ElementRecord', 'id', 'required' => true, 'onDelete' => static::CASCADE),
            'platform' => array(static::BELONGS_TO, 'PushNotifications_PlatformRecord', 'required' => true, 'onDelete' => static::CASCADE),
        );
    }
}
