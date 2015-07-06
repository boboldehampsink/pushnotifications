<?php

namespace Craft;

require_once dirname(__FILE__).'/../vendor/autoload.php';

use Sly\NotificationPusher\Adapter\Apns as ApnsAdapter;
use Sly\NotificationPusher\Adapter\Gcm as GcmAdapter;
use Sly\NotificationPusher\Adapter\Mpns as MpnsAdapter;

/**
 * Push Notifications - Platform Model.
 *
 * @author    Bob Olde Hampsink
 * @copyright Copyright (c) 2015, Bob Olde Hampsink
 * @license   MIT
 *
 * @link      https://github.com/boboldehampsink
 * @since     0.0.1
 */
class PushNotifications_PlatformModel extends BaseElementModel
{
    /**
     * Use the platform name as the string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Get platform name.
     *
     * @return string
     */
    public function getName()
    {
        foreach ($this->getOptions() as $option) {
            if ($option['value'] == $this->handle) {
                return $option['label'];
            }
        }
    }

    /**
     * Get platform options.
     *
     * @return array
     */
    public function getOptions()
    {
        return array(
            array('label' => 'iOS', 'value' => 'ios'),
            array('label' => 'Android', 'value' => 'android'),
            array('label' => 'WP8', 'value' => 'wp8'),
        );
    }

    /**
     * Get NotificationPusher adapter for platform.
     *
     * @return BaseAdapter
     */
    public function getAdapter()
    {
        switch ($this->handle) {
            case 'ios':
                return new ApnsAdapter(array(
                    'certificate' => $this->setting,
                ));
                break;

            case 'android':
                return new GcmAdapter(array(
                    'apiKey' => $this->setting,
                ));
                break;

            case 'wp8':
                return new MpnsAdapter();
                break;
        }
    }

    /**
     * @return array
     */
    protected function defineAttributes()
    {
        return array(
            'id'            => AttributeType::Number,
            'handle'        => AttributeType::String,
            'setting'       => AttributeType::String,
        );
    }
}
