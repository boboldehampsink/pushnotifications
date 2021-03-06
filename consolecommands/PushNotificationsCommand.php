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
        // Log invocation
        Craft::log($this->getCommandRunner()->getScriptName());

        // Get notification id
        $id = $args[0];

        // Validate id
        if (!is_numeric($id)) {
            $this->usageError(Craft::t('The argument must be a numeric id'));
        }

        // Get notification
        $notification = craft()->pushNotifications_notifications->getNotificationById($id);

        // Validate notification
        if (!$notification) {
            $this->usageError(Craft::t('No notification found with id "{id}"', array('id' => $id)));
        }

        try {

            // Send notification
            $platforms = craft()->pushNotifications_push->sendNotification($notification);
        } catch (\Exception $e) {
            $this->usageError($e->getMessage());
        }

        // Count devices
        $devices = 0;
        foreach ($platforms as $platform) {
            $devices += $platform;
        }

        // Show result
        echo Craft::t('Notification sent to {devices} device(s)', array('devices' => $devices))."\n";
        exit(0);
    }
}
