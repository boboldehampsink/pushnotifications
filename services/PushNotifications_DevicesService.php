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
class PushNotifications_DevicesService extends BaseApplicationComponent
{
    /**
     * Returns an device by its ID.
     *
     * @param int $deviceId
     *
     * @return PushNotifications_DeviceModel|null
     */
    public function getDeviceById($deviceId)
    {
        return craft()->elements->getElementById($deviceId, 'PushNotifications_Device');
    }

    /**
     * Saves an device.
     *
     * @param PushNotifications_DeviceModel $device
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function saveDevice(PushNotifications_DeviceModel $device)
    {
        $isNewDevice = !$device->id;

        // Device data
        if (!$isNewDevice) {
            $deviceRecord = PushNotifications_DeviceRecord::model()->findById($device->id);

            if (!$deviceRecord) {
                throw new Exception(Craft::t('No device exists with the ID “{id}”', array('id' => $device->id)));
            }
        } else {
            $deviceRecord = new PushNotifications_DeviceRecord();
        }

        $deviceRecord->appId    = $device->appId;
        $deviceRecord->platform = $device->platform;
        $deviceRecord->token    = $device->token;

        $deviceRecord->validate();
        $device->addErrors($deviceRecord->getErrors());

        if (!$device->hasErrors()) {
            $transaction = craft()->db->getCurrentTransaction() === null ? craft()->db->beginTransaction() : null;
            try {
                // Fire an 'onBeforeSaveDevice' event
                $this->onBeforeSaveDevice(new Event($this, array(
                    'device'      => $device,
                    'isNewDevice' => $isNewDevice,
                )));

                if (craft()->elements->saveElement($device)) {
                    // Now that we have an element ID, save it on the other stuff
                    if ($isNewDevice) {
                        $deviceRecord->id = $device->id;
                    }

                    $deviceRecord->save(false);

                    // Fire an 'onSaveDevice' event
                    $this->onSaveDevice(new Event($this, array(
                        'device'      => $device,
                        'isNewDevice' => $isNewDevice,
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
     * Fires an 'onBeforeSaveDevice' event.
     *
     * @param Event $device
     */
    public function onBeforeSaveDevice(Event $device)
    {
        $this->raiseEvent('onBeforeSaveDevice', $device);
    }

    /**
     * Fires an 'onSaveDevice' event.
     *
     * @param Event $device
     */
    public function onSaveDevice(Event $device)
    {
        $this->raiseEvent('onSaveDevice', $device);
    }
}
