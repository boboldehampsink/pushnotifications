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
     * Device index.
     */
    public function actionDeviceIndex()
    {
        $variables['platforms'] = craft()->pushNotifications_platforms->getAllPlatforms();

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
        if (!empty($variables['platformHandle'])) {
            $variables['platform'] = craft()->pushNotifications_platforms->getPlatformByHandle($variables['platformHandle']);
        } elseif (!empty($variables['platformId'])) {
            $variables['platform'] = craft()->pushNotifications_platforms->getPlatformById($variables['platformId']);
        }

        if (empty($variables['platform'])) {
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
                $variables['device']->platformId = $variables['platform']->id;
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
            array('label' => $variables['platform']->name, 'url' => UrlHelper::getUrl('pushnotifications')),
        );

        // Set the "Continue Editing" URL
        $variables['continueEditingUrl'] = 'pushnotifications/'.$variables['platform']->handle.'/{id}';

        // Render the template!
        $this->renderTemplate('pushnotifications/devices/_edit', $variables);
    }

    /**
     * Registers a device.
     */
    public function actionRegisterDevice()
    {
        $this->requireAjaxRequest();

        // Get platform handle
        $platformHandle = craft()->request->getParam('platform');

        // Get platform
        $platform = craft()->pushNotifications_platforms->getPlatformByHandle($platformHandle);

        // Get token
        $token = craft()->request->getParam('registrationId');

        // Set device
        $device = new PushNotifications_DeviceModel();
        $device->platformId = $platform->id;
        $device->token = $token;

        // Save device
        if (craft()->pushNotifications_devices->saveDevice($device)) {
            $this->returnJson(array('success' => true));
        } else {
            $this->returnErrorJson($device->getErrors());
        }
    }

    /**
     * Saves an device.
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
        $device->platformId     = craft()->request->getPost('platformId', $device->platformId);
        $device->token          = craft()->request->getPost('token', $device->token);

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
