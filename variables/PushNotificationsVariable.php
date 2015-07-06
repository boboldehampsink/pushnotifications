<?php

namespace Craft;

/**
 * Push Notifications Plugin.
 *
 * @author    Bob Olde Hampsink
 * @copyright Copyright (c) 2015, Bob Olde Hampsink
 * @license   MIT
 *
 * @link      https://github.com/boboldehampsink
 * @since     0.0.1
 */
class PushNotificationsVariable
{
    /**
     * Notifications.
     *
     * @return ElementCriteriaModel
     */
    public function notifications()
    {
        return craft()->elements->getCriteria('PushNotifications_Notification');
    }

    /**
     * Devices.
     *
     * @return ElementCriteriaModel
     */
    public function devices()
    {
        return craft()->elements->getCriteria('PushNotifications_Device');
    }
}
