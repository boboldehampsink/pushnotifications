<?php

namespace Craft;

/**
 * Push Notifications - Device Field Type.
 *
 * @author    Bob Olde Hampsink
 * @copyright Copyright (c) 2015, Bob Olde Hampsink
 * @license   MIT
 *
 * @link      https://github.com/boboldehampsink
 * @since     0.0.1
 */
class PushNotifications_DeviceFieldType extends BaseElementFieldType
{
    /**
     * @var string The element type this field deals with.
     */
    protected $elementType = 'PushNotifications_Device';

    /**
     * Returns the label for the "Add" button.
     *
     * @return string
     */
    protected function getAddButtonLabel()
    {
        return Craft::t('Add a device');
    }
}
