<?php

namespace Craft;

/**
 * Push Notifications - Notification Record.
 *
 * @author    Bob Olde Hampsink
 * @copyright Copyright (c) 2015, Bob Olde Hampsink
 * @license   MIT
 *
 * @link      https://github.com/boboldehampsink
 * @since     0.0.1
 */
class PushNotifications_NotificationRecord extends BaseRecord
{
    /**
     * @return string
     */
    public function getTableName()
    {
        return 'pushnotifications_notifications';
    }

    /**
     * @return array
     */
    protected function defineAttributes()
    {
        return array(
            'title'     => array(AttributeType::Name, 'required' => true),
            'body'      => array(AttributeType::String, 'required' => true),
            'param'     => AttributeType::String,
        );
    }

    /**
     * @return array
     */
    public function defineRelations()
    {
        return array(
            'element'  => array(static::BELONGS_TO, 'ElementRecord', 'id', 'required' => true, 'onDelete' => static::CASCADE),
            'app' => array(static::BELONGS_TO, 'PushNotifications_AppRecord', 'required' => true, 'onDelete' => static::CASCADE),
        );
    }
}
