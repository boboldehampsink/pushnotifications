<?php

namespace Craft;

/**
 * Push Notifications - App Record.
 *
 * @author    Bob Olde Hampsink
 * @copyright Copyright (c) 2015, Bob Olde Hampsink
 * @license   MIT
 *
 * @link      https://github.com/boboldehampsink
 * @since     0.0.1
 */
class PushNotifications_AppRecord extends BaseRecord
{
    /**
     * @return string
     */
    public function getTableName()
    {
        return 'pushnotifications_apps';
    }

    /**
     * @return array
     */
    protected function defineAttributes()
    {
        return array(
            'name'          => array(AttributeType::Name, 'required' => true),
            'handle'        => array(AttributeType::Handle, 'required' => true),
            'platforms'     => array(AttributeType::Mixed, 'required' => true),
            'commands'      => AttributeType::Mixed,
        );
    }

    /**
     * @return array
     */
    public function defineRelations()
    {
        return array(
            'notifications' => array(static::HAS_MANY, 'PushNotifications_NotificationRecord', 'notificationId'),
        );
    }

    /**
     * @return array
     */
    public function defineIndexes()
    {
        return array(
            array('columns' => array('name'), 'unique' => true),
            array('columns' => array('handle'), 'unique' => true),
        );
    }

    /**
     * @return array
     */
    public function scopes()
    {
        return array(
            'ordered' => array('order' => 'name'),
        );
    }
}
