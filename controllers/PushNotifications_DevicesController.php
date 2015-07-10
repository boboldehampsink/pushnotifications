<?php

namespace Craft;

/**
 * Push Notifications Devices Controller.
 *
 * @author    Bob Olde Hampsink
 * @copyright Copyright (c) 2015, Bob Olde Hampsink
 * @license   MIT
 *
 * @link      https://github.com/boboldehampsink
 * @since     0.0.1
 */
class PushNotifications_DevicesController extends BaseController
{
    /**
     * Allow anonymous access to these functions.
     */
    public $allowAnonymous = array('actionRegisterDevice');

    /**
     * Device index.
     */
    public function actionDeviceIndex()
    {
        $variables['apps'] = craft()->pushNotifications_apps->getAllApps();

        $this->renderTemplate('pushnotifications/devices/_index', $variables);
    }

    /**
     * Edit an device.
     *
     * @param array $variables
     *
     * @throws HttpException
     */
    public function actionEditDevice(array $variables = array())
    {
        if (!empty($variables['appHandle'])) {
            $variables['app'] = craft()->pushNotifications_apps->getAppByHandle($variables['appHandle']);
        } elseif (!empty($variables['appId'])) {
            $variables['app'] = craft()->pushNotifications_apps->getAppById($variables['appId']);
        }

        if (empty($variables['app'])) {
            throw new HttpException(404);
        }

        // Now let's set up the actual device
        if (empty($variables['device'])) {
            if (!empty($variables['deviceId'])) {
                $variables['device'] = craft()->pushNotifications_devices->getDeviceById($variables['deviceId']);

                if (!$variables['device']) {
                    throw new HttpException(404);
                }
            } else {
                $variables['device'] = new PushNotifications_DeviceModel();
                $variables['device']->appId = $variables['app']->id;
            }
        }

        if (!$variables['device']->id) {
            $variables['title'] = Craft::t('Create a new device');
        } else {
            $variables['title'] = $variables['device']->title;
        }

        // Breadcrumbs
        $variables['crumbs'] = array(
            array('label' => Craft::t('Push Notifications'), 'url' => UrlHelper::getUrl('pushnotifications')),
            array('label' => $variables['app']->name, 'url' => UrlHelper::getUrl('pushnotifications')),
        );

        // Set the "Continue Editing" URL
        $variables['continueEditingUrl'] = 'pushnotifications/devices/'.$variables['app']->handle.'/{id}';

        // Render the template!
        $this->renderTemplate('pushnotifications/devices/_edit', $variables);
    }

    /**
     * Registers a device.
     */
    public function actionRegisterDevice()
    {
        // Get app handle
        $appHandle = craft()->request->getParam('app');

        // Get app
        $app = craft()->pushNotifications_apps->getAppByHandle($appHandle);

        // Get platform
        $platform = craft()->request->getParam('platform');

        // Get token
        $token = craft()->request->getParam('registrationId');

        // First try and see if we've already got this
        $criteria = craft()->elements->getCriteria('PushNotifications_Device');
        $criteria->appId = $app->id;
        $criteria->platform = $platform;
        $criteria->token = $token;

        if (!$criteria->total()) {

            // Set new device
            $device = new PushNotifications_DeviceModel();
            $device->appId      = $app->id;
            $device->platform   = $platform;
            $device->token      = $token;

            // Save device
            if (!craft()->pushNotifications_devices->saveDevice($device)) {
                $this->returnErrorJson($device->getErrors());
            }
        }

        $this->returnJson(array('success' => true));
    }

    /**
     * Unregisters a device.
     */
    public function actionUnregisterDevice()
    {
        // Get app handle
        $appHandle = craft()->request->getParam('app');

        // Get app
        $app = craft()->pushNotifications_apps->getAppByHandle($appHandle);

        // Get platform
        $platform = craft()->request->getParam('platform');

        // Get token
        $token = craft()->request->getParam('registrationId');

        // First try and see if we've got this
        $criteria = craft()->elements->getCriteria('PushNotifications_Device');
        $criteria->appId = $app->id;
        $criteria->platform = $platform;
        $criteria->token = $token;

        // Get device
        if ($device = $criteria->first()) {

            // Delete device
            if (craft()->elements->deleteElementById($device->id)) {
                $this->returnJson(array('success' => true));
            }
        }

        $this->returnErrorJson(true);
    }

    /**
     * Saves a device from the CP.
     */
    public function actionSaveDevice()
    {
        $this->requirePostRequest();

        $deviceId = craft()->request->getPost('deviceId');

        if ($deviceId) {
            $device = craft()->pushNotifications_devices->getDeviceById($deviceId);

            if (!$device) {
                throw new Exception(Craft::t('No device exists with the ID “{id}”', array('id' => $deviceId)));
            }
        } else {
            $device = new PushNotifications_DeviceModel();
        }

        // Set the device attributes, defaulting to the existing values for whatever is missing from the post data
        $device->appId      = craft()->request->getPost('appId', $device->appId);
        $device->platform   = craft()->request->getPost('platform', $device->platform);
        $device->token      = craft()->request->getPost('token', $device->token);

        if (craft()->pushNotifications_devices->saveDevice($device)) {
            craft()->userSession->setNotice(Craft::t('Device saved.'));
            $this->redirectToPostedUrl($device);
        } else {
            craft()->userSession->setError(Craft::t('Couldn’t save device.'));

            // Send the device back to the template
            craft()->urlManager->setRouteVariables(array(
                'device' => $device,
            ));
        }
    }

    /**
     * Deletes an device.
     */
    public function actionDeleteDevice()
    {
        $this->requirePostRequest();

        $deviceId = craft()->request->getRequiredPost('deviceId');

        if (craft()->elements->deleteElementById($deviceId)) {
            craft()->userSession->setNotice(Craft::t('Device deleted.'));
            $this->redirectToPostedUrl();
        } else {
            craft()->userSession->setError(Craft::t('Couldn’t delete device.'));
        }
    }
}
