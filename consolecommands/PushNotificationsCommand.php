<?php

namespace Craft;

/**
 * Push Notifications Command.
 *
 * @author    Bob Olde Hampsink
 * @copyright Copyright (c) 2015, Bob Olde Hampsink
 * @license   MIT
 *
 * @link      https://github.com/boboldehampsink
 * @since     0.2.0
 */
class PushNotificationsCommand extends BaseCommand
{
    // Properties
    // =========================================================================

    /**
     * @var string
     */
    public $defaultAction = 'send';

    /**
     * Sends the push notification.
     *
     * @param $args
     *
     * @return int
     */
    public function actionSend($args)
    {
        // Get notification id
        $id = $args[0];

        // Validate id
        if (!is_numeric($id)) {
            echo Craft::t('The argument must be a numeric id')."\n";

            return;
        }

        // Get notification
        $notification = craft()->pushNotifications_notifications->getNotificationById($id);

        // Validate notification
        if (!$notification) {
            echo Craft::t('No notification found with id "{id}"', array('id' => $id))."\n";

            return;
        }

        try {

            // Send notification
            $devices = craft()->pushNotifications_push->sendNotification($notification);
        } catch (\Exception $e) {
            echo $e->getMessage()."\n";

            return;
        }

        // Show result
        echo Craft::t('Notification sent to {devices} device(s)', array('devices' => count($devices)))."\n";

        return;
    }
}
