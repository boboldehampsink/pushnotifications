<?php

namespace Craft;

/**
 * Push Notifications - Notification Model.
 *
 * @author    Bob Olde Hampsink
 * @copyright Copyright (c) 2015, Bob Olde Hampsink
 * @license   MIT
 *
 * @link      https://github.com/boboldehampsink
 * @since     0.0.1
 */
class PushNotifications_NotificationModel extends BaseElementModel
{
    protected $elementType = 'PushNotifications_Notification';

    /**
     * @return array
     */
    protected function defineAttributes()
    {
        return array_merge(parent::defineAttributes(), array(
            'appId'     => AttributeType::Number,
            'title'     => AttributeType::Name,
            'body'      => AttributeType::String,
            'command'   => AttributeType::String,
            'schedule'  => array(AttributeType::DateTime, 'default' => DateTimeHelper::currentUTCDateTime()),
        ));
    }

    /**
     * Get model title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Returns whether the current user can edit the element.
     *
     * @return bool
     */
    public function isEditable()
    {
        return true;
    }

    /**
     * Returns the element's CP edit URL.
     *
     * @return string|false
     */
    public function getCpEditUrl()
    {
        $app = $this->getApp();

        if ($app) {
            return UrlHelper::getCpUrl('pushnotifications/notifications/'.$app->handle.'/'.$this->id);
        }
    }

    /**
     * Returns the app the device was registered with.
     *
     * @return PushNotifications_AppModel|null
     */
    public function getApp()
    {
        if ($this->appId) {
            return craft()->pushNotifications_apps->getAppById($this->appId);
        }
    }
}
