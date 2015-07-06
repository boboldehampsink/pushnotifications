<?php

namespace Craft;

/**
 * Push Notifications - Platform Service.
 *
 * @author    Bob Olde Hampsink
 * @copyright Copyright (c) 2015, Bob Olde Hampsink
 * @license   MIT
 *
 * @link      https://github.com/boboldehampsink
 * @since     0.0.1
 */
class PushNotifications_PlatformsService extends BaseApplicationComponent
{
    private $_allPlatformIds;
    private $_platformsById;
    private $_fetchedAllPlatforms = false;

    /**
     * Returns all of the platform IDs.
     *
     * @return array
     */
    public function getAllPlatformIds()
    {
        if (!isset($this->_allPlatformIds)) {
            if ($this->_fetchedAllPlatforms) {
                $this->_allPlatformIds = array_keys($this->_platformsById);
            } else {
                $this->_allPlatformIds = craft()->db->createCommand()
                    ->select('id')
                    ->from('devices_platforms')
                    ->queryColumn();
            }
        }

        return $this->_allPlatformIds;
    }

    /**
     * Returns all platforms.
     *
     * @param string|null $indexBy
     *
     * @return array
     */
    public function getAllPlatforms($indexBy = null)
    {
        if (!$this->_fetchedAllPlatforms) {
            $platformRecords = PushNotifications_PlatformRecord::model()->ordered()->findAll();
            $this->_platformsById = PushNotifications_PlatformModel::populateModels($platformRecords, 'id');
            $this->_fetchedAllPlatforms = true;
        }

        if ($indexBy == 'id') {
            return $this->_platformsById;
        } elseif (!$indexBy) {
            return array_values($this->_platformsById);
        } else {
            $platforms = array();

            foreach ($this->_platformsById as $platform) {
                $platforms[$platform->$indexBy] = $platform;
            }

            return $platforms;
        }
    }

    /**
     * Gets the total number of platforms.
     *
     * @return int
     */
    public function getTotalPlatforms()
    {
        return count($this->getAllPlatformsIds());
    }

    /**
     * Returns a platform by its ID.
     *
     * @param $platformId
     *
     * @return PushNotifications_PlatformModel|null
     */
    public function getPlatformById($platformId)
    {
        if (!isset($this->_platformsById) || !array_key_exists($platformId, $this->_platformsById)) {
            $platformRecord = PushNotifications_PlatformRecord::model()->findById($platformId);

            if ($platformRecord) {
                $this->_platformsById[$platformId] = PushNotifications_PlatformModel::populateModel($platformRecord);
            } else {
                $this->_platformsById[$platformId] = null;
            }
        }

        return $this->_platformsById[$platformId];
    }

    /**
     * Gets a platform by its handle.
     *
     * @param string $platformHandle
     *
     * @return PushNotifications_PlatformModel|null
     */
    public function getPlatformByHandle($platformHandle)
    {
        $platformRecord = PushNotifications_PlatformRecord::model()->findByAttributes(array(
            'handle' => $platformHandle,
        ));

        if ($platformRecord) {
            return PushNotifications_PlatformModel::populateModel($platformRecord);
        }
    }

    /**
     * Saves a platform.
     *
     * @param PushNotifications_PlatformModel $platform
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function savePlatform(PushNotifications_PlatformModel $platform)
    {
        if ($platform->id) {
            $platformRecord = PushNotifications_PlatformRecord::model()->findById($platform->id);

            if (!$platformRecord) {
                throw new Exception(Craft::t('No platform exists with the ID “{id}”', array('id' => $platform->id)));
            }
        } else {
            $platformRecord = new PushNotifications_PlatformRecord();
        }

        $platformRecord->handle     = $platform->handle;
        $platformRecord->setting    = $platform->setting;

        $platformRecord->validate();
        $platform->addErrors($platformRecord->getErrors());

        if (!$platform->hasErrors()) {
            $transaction = craft()->db->getCurrentTransaction() === null ? craft()->db->beginTransaction() : null;
            try {

                // Save it!
                $platformRecord->save(false);

                // Now that we have a platform ID, save it on the model
                if (!$platform->id) {
                    $platform->id = $platformRecord->id;
                }

                // Might as well update our cache of the platform while we have it.
                $this->_platformsById[$platform->id] = $platform;

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
     * Deletes a platform by its ID.
     *
     * @param int $platformId
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function deletePlatformById($platformId)
    {
        if (!$platformId) {
            return false;
        }

        $transaction = craft()->db->getCurrentTransaction() === null ? craft()->db->beginTransaction() : null;
        try {

            // Grab the device ids so we can clean the elements table.
            $deviceIds = craft()->db->createCommand()
                ->select('id')
                ->from('devices')
                ->where(array('platformId' => $platformId))
                ->queryColumn();

            craft()->elements->deleteElementById($deviceIds);

            $affectedRows = craft()->db->createCommand()->delete('devices_platforms', array('id' => $platformId));

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
