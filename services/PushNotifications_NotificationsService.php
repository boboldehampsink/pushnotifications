<?php

namespace Craft;

/**
 * Push Notifications Service.
 *
 * @author    Bob Olde Hampsink
 * @copyright Copyright (c) 2015, Bob Olde Hampsink
 * @license   MIT
 *
 * @link      https://github.com/boboldehampsink
 * @since     0.0.1
 */
class PushNotifications_NotificationsService extends BaseApplicationComponent
{
    /**
     * Returns an notification by its ID.
     *
     * @param int $notificationId
     *
     * @return PushNotifications_NotificationModel|null
     */
    public function getNotificationById($notificationId)
    {
        return craft()->elements->getElementById($notificationId, 'PushNotifications_Notification');
    }

    /**
     * Saves an notification.
     *
     * @param PushNotifications_NotificationModel $notification
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function saveNotification(PushNotifications_NotificationModel $notification)
    {
        $isNewNotification = !$notification->id;

        // Notification data
        if (!$isNewNotification) {
            $notificationRecord = PushNotifications_NotificationRecord::model()->findById($notification->id);

            if (!$notificationRecord) {
                throw new Exception(Craft::t('No notification exists with the ID “{id}”', array('id' => $notification->id)));
            }
        } else {
            $notificationRecord = new PushNotifications_NotificationRecord();
        }

        $notificationRecord->appId     = $notification->appId;
        $notificationRecord->title     = $notification->title;
        $notificationRecord->body      = $notification->body;
        $notificationRecord->command   = $notification->command;

        $notificationRecord->validate();
        $notification->addErrors($notificationRecord->getErrors());

        if (!$notification->hasErrors()) {
            $transaction = craft()->db->getCurrentTransaction() === null ? craft()->db->beginTransaction() : null;
            try {
                // Fire an 'onBeforeSaveNotification' event
                $this->onBeforeSaveNotification(new Event($this, array(
                    'notification'      => $notification,
                    'isNewNotification' => $isNewNotification,
                )));

                if (craft()->elements->saveElement($notification)) {
                    // Now that we have an element ID, save it on the other stuff
                    if ($isNewNotification) {
                        $notificationRecord->id = $notification->id;
                    }

                    $notificationRecord->save(false);

                    // Fire an 'onSaveNotification' event
                    $this->onSaveNotification(new Event($this, array(
                        'notification'      => $notification,
                        'isNewNotification' => $isNewNotification,
                    )));

                    if ($transaction !== null) {
                        $transaction->commit();
                    }

                    return true;
                }
            } catch (\Exception $e) {
                if ($transaction !== null) {
                    $transaction->rollback();
                }

                throw $e;
            }
        }

        return false;
    }

    // Events

    /**
     * Fires an 'onBeforeSaveNotification' event.
     *
     * @param Event $notification
     */
    public function onBeforeSaveNotification(Event $notification)
    {
        $this->raiseEvent('onBeforeSaveNotification', $notification);
    }

    /**
     * Fires an 'onSaveNotification' event.
     *
     * @param Event $notification
     */
    public function onSaveNotification(Event $notification)
    {
        $this->raiseEvent('onSaveNotification', $notification);
    }
}
