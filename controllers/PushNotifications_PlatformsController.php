<?php

namespace Craft;

/**
 * Push Notifications Platforms Controller.
 *
 * @author    Bob Olde Hampsink
 * @copyright Copyright (c) 2015, Bob Olde Hampsink
 * @license   MIT
 *
 * @link      https://github.com/boboldehampsink
 * @since     0.0.1
 */
class PushNotifications_PlatformsController extends BaseController
{
    /**
     * Platforms index.
     */
    public function actionPlatformIndex()
    {
        $this->requireAdmin();

        $variables['platforms'] = craft()->pushNotifications_platforms->getAllPlatforms();

        $this->renderTemplate('pushnotifications/platforms', $variables);
    }

    /**
     * Edit a platform.
     *
     * @param array $variables
     *
     * @throws HttpException
     * @throws Exception
     */
    public function actionEditPlatform(array $variables = array())
    {
        $this->requireAdmin();

        $variables['brandNewPlatform'] = false;

        if (!empty($variables['platformId'])) {
            if (empty($variables['platform'])) {
                $variables['platform'] = craft()->pushNotifications_platforms->getPlatformById($variables['platformId']);

                if (!$variables['platform']) {
                    throw new HttpException(404);
                }
            }

            $variables['title'] = $variables['platform']->name;
        } else {
            if (empty($variables['platform'])) {
                $variables['platform'] = new PushNotifications_PlatformModel();
                $variables['brandNewPlatform'] = true;
            }

            $variables['title'] = Craft::t('Create a new platform');
        }

        $variables['crumbs'] = array(
            array('label' => Craft::t('Push Notifications'), 'url' => UrlHelper::getUrl('pushnotifications')),
            array('label' => Craft::t('Platforms'), 'url' => UrlHelper::getUrl('pushnotifications/platforms')),
        );

        $this->renderTemplate('pushnotifications/platforms/_edit', $variables);
    }

    /**
     * Saves a platform.
     */
    public function actionSavePlatform()
    {
        $this->requireAdmin();
        $this->requirePostRequest();

        $platform = new PushNotifications_PlatformModel();

        // Shared attributes
        $platform->id         = craft()->request->getPost('platformId');
        $platform->handle     = craft()->request->getPost('handle');
        $platform->setting    = craft()->request->getPost('setting');

        // Save it
        if (craft()->pushNotifications_platforms->savePlatform($platform)) {
            craft()->userSession->setNotice(Craft::t('Platform saved.'));
            $this->redirectToPostedUrl($platform);
        } else {
            craft()->userSession->setError(Craft::t('Couldn’t save platform.'));
        }

        // Send the platform back to the template
        craft()->urlManager->setRouteVariables(array(
            'platform' => $platform,
        ));
    }

    /**
     * Deletes a platform.
     */
    public function actionDeletePlatform()
    {
        $this->requireAdmin();
        $this->requirePostRequest();
        $this->requireAjaxRequest();

        $platformId = craft()->request->getRequiredPost('id');

        craft()->pushNotifications_platforms->deletePlatformById($platformId);
        $this->returnJson(array('success' => true));
    }
}
