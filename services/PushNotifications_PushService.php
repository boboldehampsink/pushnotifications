<?php

namespace Craft;

require_once dirname(__FILE__).'/../vendor/autoload.php';

use Sly\NotificationPusher\PushManager;
use Sly\NotificationPusher\Collection\DeviceCollection;
use Sly\NotificationPusher\Model\Device;
use Sly\NotificationPusher\Model\Message;
use Sly\NotificationPusher\Model\Push;
use Sly\NotificationPusher\Adapter\Apns as ApnsAdapter;
use Sly\NotificationPusher\Adapter\Gcm as GcmAdapter;
use Sly\NotificationPusher\Adapter\Wns as WnsAdapter;

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
     * Schedule a notification.
     *
     * @param PushNotifications_NotificationModel $notification
     */
    public function scheduleNotification(PushNotifications_NotificationModel $notification)
    {
        // Get schedule
        $schedule = $notification->schedule;

        // Initialize the cronjob repository
        $adapter    = new Cronjob_AdapterModel();
        $repository = new Cronjob_RepositoryModel($adapter);

        // Check if cronjob exists
        $results = $repository->findJobByRegex('/^'.$notification->id.'$/');
        $cronjob = isset($results[0]) ? $results[0] : new CronjobModel();

        // Set up cronjob
        $cronjob->minutes           = $schedule->format('i');
        $cronjob->hours             = $schedule->format('H');
        $cronjob->dayOfMonth        = $schedule->format('d');
        $cronjob->months            = $schedule->format('m');
        $cronjob->dayOfWeek         = $schedule->format('w');
        $cronjob->taskCommandLine   = CRAFT_APP_PATH.'etc/console/yiic pushnotifications send '.$notification->id;
        $cronjob->comments          = $notification->id;

        // Now save the cronjob
        if (!isset($results[0])) {
            $repository->addJob($cronjob);
        }
        $repository->persist();
    }

    /**
     * Sends a notification.
     *
     * @param PushNotifications_NotificationModel $notification
     *
     * @return DeviceCollection[]
     */
    public function sendNotification(PushNotifications_NotificationModel $notification)
    {
        // Get max power
        craft()->config->maxPowerCaptain();

        // Determine environment
        $environment = craft()->config->get('devMode') ? PushManager::ENVIRONMENT_DEV : PushManager::ENVIRONMENT_PROD;

        // Get app
        $app = $notification->getApp();

        // Start pushmanager
        $pushManager = new PushManager($environment);

        // Gather notified devices
        $notified = array();

        // Loop through platforms
        foreach ($app->getEnabledPlatforms() as $platform => $setting) {

            // Gather devices
            $devices = array();

            // Get devices by platform
            $criteria = craft()->elements->getCriteria('PushNotifications_Device');
            $criteria->app = $app->handle;
            $criteria->platform = $platform;

            // Check if we have results
            if ($criteria->count()) {

                // Loop through devices
                foreach ($criteria->find() as $device) {

                    // Grab device instance
                    $devices[] = new Device($device->token);
                }

                // Parse device collection
                $devices = new DeviceCollection($devices);

                // Set the push message and the options
                $message = new Message($notification->body, array(
                    'title'   => $notification->title,
                    'custom'  => array('command' => $notification->command),
                ));

                // Finally, create and add the push to the manager, and push it!
                $push = new Push($this->getAdapter($platform, $setting), $devices, $message);
                $pushManager->add($push);
                $notified[$platform] = $pushManager->push();
            }
        }

        // Returns a collection of notified devices per platform
        return $notified;
    }

    /**
     * Get NotificationPusher adapter for platform.
     *
     * @param string $platform
     * @param string $setting
     *
     * @return BaseAdapter
     */
    public function getAdapter($platform, $setting)
    {
        switch ($platform) {
            case PushNotifications_AppModel::PLATFORM_IOS:
                return new ApnsAdapter(array(
                    'certificate' => $setting,
                ));
                break;

            case PushNotifications_AppModel::PLATFORM_ANDROID:
                return new GcmAdapter(array(
                    'apiKey' => $setting,
                ));
                break;

            case PushNotifications_AppModel::PLATFORM_WINDOWS:
                return new WnsAdapter(array(

                ));
                break;
        }
    }
}
