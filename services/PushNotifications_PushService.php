<?php

namespace Craft;

require_once dirname(__FILE__).'/../vendor/autoload.php';

use Sly\NotificationPusher\PushManager;
use Sly\NotificationPusher\Collection\DeviceCollection;
use Sly\NotificationPusher\Model\Device;
use Sly\NotificationPusher\Model\Message;
use Sly\NotificationPusher\Model\Push;

/**
 * Push Notifications Push Service.
 *
 * @author    Bob Olde Hampsink
 * @copyright Copyright (c) 2015, Bob Olde Hampsink
 * @license   MIT
 *
 * @link      https://github.com/boboldehampsink
 * @since     0.0.1
 */
class PushNotifications_PushService extends BaseApplicationComponent
{
    /**
     * Sends a notification.
     *
     * @param PushNotifications_NotificationModel $notification
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function sendNotification(PushNotifications_NotificationModel $notification)
    {
        // Determine environment
        $environment = craft()->config->get('devMode') ? PushManager::ENVIRONMENT_DEV : PushManager::ENVIRONMENT_PROD;

        // Start pushmanager
        $pushManager = new PushManager($environment);

        // Loop through platforms
        foreach (craft()->pushNotifications_platforms->getAllPlatforms() as $platform) {

            // Gather devices
            $devices = array();

            // Get devices by platform
            $criteria = craft()->elements->getCriteria('PushNotifications_Device');
            $criteria->platform = $platform->handle;

            // Loop through devices
            foreach ($criteria->find() as $device) {

                // Grab device instance
                $devices[] = new Device($device->token, $device->parameters);
            }

            // Parse device collection
            $devices = new DeviceCollection($devices);

            // Set the push message
            $message = new Message($notification->body);

            // Finally, create and add the push to the manager, and push it!
            $push = new Push($platform->adapter, $devices, $message);
            $pushManager->add($push);
            $pushManager->push(); // Returns a collection of notified devices
        }
    }
}
