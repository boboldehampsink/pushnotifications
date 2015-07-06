<?php

namespace Craft;

/**
 * Push Notifications - App Service.
 *
 * @author    Bob Olde Hampsink
 * @copyright Copyright (c) 2015, Bob Olde Hampsink
 * @license   MIT
 *
 * @link      https://github.com/boboldehampsink
 * @since     0.0.1
 */
class PushNotifications_AppsService extends BaseApplicationComponent
{
    private $_allAppIds;
    private $_appsById;
    private $_fetchedAllApps = false;

    /**
     * Returns all of the app IDs.
     *
     * @return array
     */
    public function getAllAppIds()
    {
        if (!isset($this->_allAppIds)) {
            if ($this->_fetchedAllApps) {
                $this->_allAppIds = array_keys($this->_appsById);
            } else {
                $this->_allAppIds = craft()->db->createCommand()
                    ->select('id')
                    ->from('notifications_apps')
                    ->queryColumn();
            }
        }

        return $this->_allAppIds;
    }

    /**
     * Returns all apps.
     *
     * @param string|null $indexBy
     *
     * @return array
     */
    public function getAllApps($indexBy = null)
    {
        if (!$this->_fetchedAllApps) {
            $appRecords = PushNotifications_AppRecord::model()->ordered()->findAll();
            $this->_appsById = PushNotifications_AppModel::populateModels($appRecords, 'id');
            $this->_fetchedAllApps = true;
        }

        if ($indexBy == 'id') {
            return $this->_appsById;
        } elseif (!$indexBy) {
            return array_values($this->_appsById);
        } else {
            $apps = array();

            foreach ($this->_appsById as $app) {
                $apps[$app->$indexBy] = $app;
            }

            return $apps;
        }
    }

    /**
     * Gets the total number of apps.
     *
     * @return int
     */
    public function getTotalApps()
    {
        return count($this->getAllAppsIds());
    }

    /**
     * Returns a app by its ID.
     *
     * @param $appId
     *
     * @return PushNotifications_AppModel|null
     */
    public function getAppById($appId)
    {
        if (!isset($this->_appsById) || !array_key_exists($appId, $this->_appsById)) {
            $appRecord = PushNotifications_AppRecord::model()->findById($appId);

            if ($appRecord) {
                $this->_appsById[$appId] = PushNotifications_AppModel::populateModel($appRecord);
            } else {
                $this->_appsById[$appId] = null;
            }
        }

        return $this->_appsById[$appId];
    }

    /**
     * Gets a app by its handle.
     *
     * @param string $appHandle
     *
     * @return PushNotifications_AppModel|null
     */
    public function getAppByHandle($appHandle)
    {
        $appRecord = PushNotifications_AppRecord::model()->findByAttributes(array(
            'handle' => $appHandle,
        ));

        if ($appRecord) {
            return PushNotifications_AppModel::populateModel($appRecord);
        }
    }

    /**
     * Saves a app.
     *
     * @param PushNotifications_AppModel $app
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function saveApp(PushNotifications_AppModel $app)
    {
        if ($app->id) {
            $appRecord = PushNotifications_AppRecord::model()->findById($app->id);

            if (!$appRecord) {
                throw new Exception(Craft::t('No app exists with the ID “{id}”', array('id' => $app->id)));
            }
        } else {
            $appRecord = new PushNotifications_AppRecord();
        }

        $appRecord->name       = $app->name;
        $appRecord->handle     = $app->handle;

        $appRecord->validate();
        $app->addErrors($appRecord->getErrors());

        if (!$app->hasErrors()) {
            $transaction = craft()->db->getCurrentTransaction() === null ? craft()->db->beginTransaction() : null;
            try {

                // Save it!
                $appRecord->save(false);

                // Now that we have a app ID, save it on the model
                if (!$app->id) {
                    $app->id = $appRecord->id;
                }

                // Might as well update our cache of the app while we have it.
                $this->_appsById[$app->id] = $app;

                if ($transaction !== null) {
                    $transaction->commit();
                }
            } catch (\Exception $e) {
                if ($transaction !== null) {
                    $transaction->rollback();
                }

                throw $e;
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * Deletes a app by its ID.
     *
     * @param int $appId
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function deleteAppById($appId)
    {
        if (!$appId) {
            return false;
        }

        $transaction = craft()->db->getCurrentTransaction() === null ? craft()->db->beginTransaction() : null;
        try {

            // Grab the notification ids so we can clean the elements table.
            $notificationIds = craft()->db->createCommand()
                ->select('id')
                ->from('notifications')
                ->where(array('appId' => $appId))
                ->queryColumn();

            craft()->elements->deleteElementById($notificationIds);

            $affectedRows = craft()->db->createCommand()->delete('notifications_apps', array('id' => $appId));

            if ($transaction !== null) {
                $transaction->commit();
            }

            return (bool) $affectedRows;
        } catch (\Exception $e) {
            if ($transaction !== null) {
                $transaction->rollback();
            }

            throw $e;
        }
    }
}
