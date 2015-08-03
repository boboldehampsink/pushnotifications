<?php

namespace Craft;

/**
 * Push Notifications Notification Controller.
 *
 * @author    Bob Olde Hampsink
 * @copyright Copyright (c) 2015, Bob Olde Hampsink
 * @license   MIT
 *
 * @link      https://github.com/boboldehampsink
 * @since     0.0.1
 */
class PushNotifications_NotificationController extends BaseController
{
    /**
     * Notification index.
     */
    public function actionNotificationIndex()
    {
        $variables['apps'] = craft()->pushNotifications_apps->getAllApps();

        $this->renderTemplate('pushnotifications/notifications/_index', $variables);
    }

    /**
     * Edit an notification.
     *
     * @param array $variables
     *
     * @throws HttpException
     */
    public function actionEditNotification(array $variables = array())
    {
        // Get app
        if (!empty($variables['appHandle'])) {
            $variables['app'] = craft()->pushNotifications_apps->getAppByHandle($variables['appHandle']);
        } elseif (!empty($variables['appId'])) {
            $variables['app'] = craft()->pushNotifications_apps->getAppById($variables['appId']);
        }
        if (empty($variables['app'])) {
            throw new HttpException(404);
        }

        // Now let's set up the actual notification
        if (empty($variables['notification'])) {
            if (!empty($variables['notificationId'])) {
                $variables['notification'] = craft()->pushNotifications_notifications->getNotificationById($variables['notificationId']);

                if (!$variables['notification']) {
                    throw new HttpException(404);
                }
            } else {
                $variables['notification'] = new PushNotifications_NotificationModel();
                $variables['notification']->appId = $variables['app']->id;
            }
        }

        // Title
        if (!$variables['notification']->id) {
            $variables['title'] = Craft::t('Create a new notification');
        } else {
            $variables['title'] = $variables['notification']->title;
        }

        // Breadcrumbs
        $variables['crumbs'] = array(
            array('label' => Craft::t('Push Notifications'), 'url' => UrlHelper::getUrl('pushnotifications')),
            array('label' => $variables['app']->name, 'url' => UrlHelper::getUrl('pushnotifications')),
        );

        // Set the "Continue Editing" URL
        $variables['continueEditingUrl'] = 'pushnotifications/'.$variables['app']->handle.'/{id}';

        // Do we have the ability to use cronjobs?
        $variables['cronjob'] = craft()->plugins->getPlugin('cronjob');

        // Render the template!
        $this->renderTemplate('pushnotifications/notifications/_edit', $variables);
    }

    /**
     * Saves an notification.
     */
    public function actionSaveNotification()
    {
        $this->requirePostRequest();

        $notificationId = craft()->request->getPost('notificationId');

        if ($notificationId) {
            $notification = craft()->pushNotifications_notifications->getNotificationById($notificationId);

            if (!$notification) {
                throw new Exception(Craft::t('No notification exists with the ID “{id}”', array('id' => $notificationId)));
            }
        } else {
            $notification = new PushNotifications_NotificationModel();
        }

        // Set the notification attributes, defaulting to the existing values for whatever is missing from the post data
        $notification->appId    = craft()->request->getPost('appId', $notification->appId);
        $notification->title    = craft()->request->getPost('title', $notification->title);
        $notification->body     = craft()->request->getPost('body', $notification->body);
        $notification->command  = craft()->request->getPost('command', $notification->command);
        $notification->schedule = (($schedule = craft()->request->getPost('schedule')) ? DateTime::createFromString($schedule, craft()->timezone) : $notification->schedule);

        // Save the notification
        if (craft()->pushNotifications_notifications->saveNotification($notification)) {
            craft()->userSession->setNotice(Craft::t('Notification saved.'));
            $this->redirectToPostedUrl($notification);
        } else {
            craft()->userSession->setError(Craft::t('Couldn’t save notification.'));

            // Send the notification back to the template
            craft()->urlManager->setRouteVariables(array(
                'notification' => $notification,
            ));
        }
    }

    /**
     * Deletes an notification.
     */
    public function actionDeleteNotification()
    {
        $this->requirePostRequest();

        // Get notification id
        $notificationId = craft()->request->getRequiredPost('notificationId');

        // Check if we're using the cronjob manager
        if (craft()->plugins->getPlugin('cronjob')) {

            // Remove cronjob
            $repository = new Cronjob_RepositoryModel(new Cronjob_AdapterModel());
            $results = $repository->findJobByRegex('/^'.$notificationId.'$/');
            if (isset($results[0])) {
                $repository->removeJob($results[0]);
                $repository->persist();
            }
        }

        // Delete the element
        if (craft()->elements->deleteElementById($notificationId)) {
            craft()->userSession->setNotice(Craft::t('Notification deleted.'));
            $this->redirectToPostedUrl();
        } else {
            craft()->userSession->setError(Craft::t('Couldn’t delete notification.'));
        }
    }
}
