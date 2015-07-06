<?php

namespace Craft;

/**
 * Push Notifications - Device Model.
 *
 * @author    Bob Olde Hampsink
 * @copyright Copyright (c) 2015, Bob Olde Hampsink
 * @license   MIT
 *
 * @link      https://github.com/boboldehampsink
 * @since     0.0.1
 */
class PushNotifications_DeviceModel extends BaseElementModel
{
    protected $elementType = 'PushNotifications_Device';

    /**
     * @return array
     */
    protected function defineAttributes()
    {
        return array_merge(parent::defineAttributes(), array(
            'platformId' => AttributeType::Number,
            'token'      => AttributeType::DateTime,
        ));
    }

    /**
     * Returns whether the current user can edit the element.
     *
     * @return bool
     */
    public function isEditable()
    {
        return false;
    }

    /**
     * Returns the element's CP edit URL.
     *
     * @return string|false
     */
    public function getCpEditUrl()
    {
        $platform = $this->getPlatform();

        if ($platform) {
            return UrlHelper::getCpUrl('pushnotifications/devices/'.$platform->handle.'/'.$this->id);
        }
    }

    /**
     * Returns the device's platform.
     *
     * @return PushNotifications_PlatformModel|null
     */
    public function getPlatform()
    {
        if ($this->platformId) {
            return craft()->pushNotifications_platforms->getPlatformById($this->platformId);
        }
    }
}
