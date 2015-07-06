<?php

namespace Craft;

/**
 * Push Notifications - Notification Field Type.
 *
 * @author    Bob Olde Hampsink
 * @copyright Copyright (c) 2015, Bob Olde Hampsink
 * @license   MIT
 *
 * @link      https://github.com/boboldehampsink
 * @since     0.0.1
 */
class PushNotifications_NotificationFieldType extends BaseElementFieldType
{
    /**
     * @var string The element type this field deals with.
     */
    protected $elementType = 'PushNotifications_Notification';

    /**
     * Returns the label for the "Add" button.
     *
     * @return string
     */
    protected function getAddButtonLabel()
    {
        return Craft::t('Add a notification');
    }
}
