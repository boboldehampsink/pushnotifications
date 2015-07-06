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
class PushNotificationsPlugin extends BasePlugin
{
    /**
     * Get plugin name.
     *
     * @return string
     */
    public function getName()
    {
        return Craft::t('Push Notifications');
    }

    /**
     * Get plugin version.
     *
     * @return string
     */
    public function getVersion()
    {
        return '0.0.1';
    }

    /**
     * Get plugin developer.
     *
     * @return string
     */
    public function getDeveloper()
    {
        return 'Bob Olde Hampsink';
    }

    /**
     * Get plugin developer url.
     *
     * @return string
     */
    public function getDeveloperUrl()
    {
        return 'https://github.com/boboldehampsink';
    }

    /**
     * Plugin has CP section?
     *
     * @return bool
     */
    public function hasCpSection()
    {
        return true;
    }

    /**
     * Return settings url.
     *
     * @return string
     */
    public function getSettingsUrl()
    {
        return 'pushnotifications/apps';
    }

    /**
     * Register CP routes.
     *
     * @return array
     */
    public function registerCpRoutes()
    {
        return array(
            'pushnotifications/apps' => array('action' => 'pushNotifications/apps/appIndex'),
            'pushnotifications/apps/new' => array('action' => 'pushNotifications/apps/editApp'),
            'pushnotifications/apps/(?P<appId>\d+)' => array('action' => 'pushNotifications/apps/editApp'),
            'pushnotifications/platforms' => array('action' => 'pushNotifications/platforms/platformIndex'),
            'pushnotifications/platforms/new' => array('action' => 'pushNotifications/platforms/editPlatform'),
            'pushnotifications/platforms/(?P<platformId>\d+)' => array('action' => 'pushNotifications/platforms/editPlatform'),
            'pushnotifications/devices' => array('action' => 'pushNotifications/devices/deviceIndex'),
            'pushnotifications/devices/(?P<platformHandle>{handle})/new' => array('action' => 'pushNotifications/devices/editDevice'),
            'pushnotifications/devices/(?P<platformHandle>{handle})/(?P<deviceId>\d+)' => array('action' => 'pushNotifications/devices/editDevice'),
            'pushnotifications' => array('action' => 'pushNotifications/notification/notificationIndex'),
            'pushnotifications/(?P<appHandle>{handle})/new' => array('action' => 'pushNotifications/notification/editNotification'),
            'pushnotifications/(?P<appHandle>{handle})/(?P<notificationId>\d+)' => array('action' => 'pushNotifications/notification/editNotification'),
        );
    }
}