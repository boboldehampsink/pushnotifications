<?php

namespace Craft;

/**
 * Push Notifications Apps Controller.
 *
 * @author    Bob Olde Hampsink
 * @copyright Copyright (c) 2015, Bob Olde Hampsink
 * @license   MIT
 *
 * @link      https://github.com/boboldehampsink
 * @since     0.0.1
 */
class PushNotifications_AppsController extends BaseController
{
    /**
     * Apps index.
     */
    public function actionAppIndex()
    {
        $variables['apps'] = craft()->pushNotifications_apps->getAllApps();

        $this->renderTemplate('pushnotifications/apps', $variables);
    }

    /**
     * Edit a app.
     *
     * @param array $variables
     *
     * @throws HttpException
     * @throws Exception
     */
    public function actionEditApp(array $variables = array())
    {
        $variables['brandNewApp'] = false;

        if (!empty($variables['appId'])) {
            if (empty($variables['app'])) {
                $variables['app'] = craft()->pushNotifications_apps->getAppById($variables['appId']);

                if (!$variables['app']) {
                    throw new HttpException(404);
                }
            }

            $variables['title'] = $variables['app']->name;
        } else {
            if (empty($variables['app'])) {
                $variables['app'] = new PushNotifications_AppModel();
                $variables['brandNewApp'] = true;
            }

            $variables['title'] = Craft::t('Create a new app');
        }

        $variables['crumbs'] = array(
            array('label' => Craft::t('Push Notifications'), 'url' => UrlHelper::getUrl('pushnotifications')),
            array('label' => Craft::t('Apps'), 'url' => UrlHelper::getUrl('pushnotifications/apps')),
        );

        $this->renderTemplate('pushnotifications/apps/_edit', $variables);
    }

    /**
     * Saves a app.
     */
    public function actionSaveApp()
    {
        $this->requirePostRequest();

        // Create new app model
        $app = new PushNotifications_AppModel();
        $app->id         = craft()->request->getPost('appId');
        $app->name       = craft()->request->getPost('name');
        $app->handle     = craft()->request->getPost('handle');
        $app->platforms  = craft()->request->getPost('platforms');
        $app->commands   = craft()->request->getPost('commands');

        // Save it
        if (craft()->pushNotifications_apps->saveApp($app)) {
            craft()->userSession->setNotice(Craft::t('App saved.'));
            $this->redirectToPostedUrl($app);
        } else {
            craft()->userSession->setError(Craft::t('Couldn’t save app.'));
        }

        // Send the app back to the template
        craft()->urlManager->setRouteVariables(array(
            'app' => $app,
        ));
    }

    /**
     * Deletes a app.
     */
    public function actionDeleteApp()
    {
        $this->requirePostRequest();
        $this->requireAjaxRequest();

        $appId = craft()->request->getRequiredPost('id');

        craft()->pushNotifications_apps->deleteAppById($appId);
        $this->returnJson(array('success' => true));
    }
}
