<?php

namespace Craft;

/**
 * Push Notifications - App Model.
 *
 * @author    Bob Olde Hampsink
 * @copyright Copyright (c) 2015, Bob Olde Hampsink
 * @license   MIT
 *
 * @link      https://github.com/boboldehampsink
 * @since     0.0.1
 */
class PushNotifications_AppModel extends BaseElementModel
{
    /**
     * Platform constants.
     */
    const PLATFORM_IOS = 'ios';
    const PLATFORM_ANDROID = 'android';

    /**
     * Use the translated app name as the string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return Craft::t($this->name);
    }

    /**
     * Returns whether the current user can edit the element.
     *
     * @return bool
     */
    public function isEditable()
    {
        return true;
    }

    /**
     * Get command options.
     *
     * @return array
     */
    public function getOptions()
    {
        $options = array();
        foreach ($this->commands as $command) {
            $options[] = array('label' => $command['name'], 'value' => $command['param']);
        }

        return $options;
    }

    /**
     * Get enabled platforms.
     *
     * @return array
     */
    public function getEnabledPlatforms()
    {
        $enabled = array();
        foreach ($this->platforms as $platform => $settings) {
            if ($settings['enabled']) {
                $enabled[$platform] = $settings['setting'];
            }
        }

        return $enabled;
    }

    /**
     * @return array
     */
    protected function defineAttributes()
    {
        return array(
            'id'            => AttributeType::Number,
            'name'          => AttributeType::String,
            'handle'        => AttributeType::String,
            'platforms'     => array(AttributeType::Mixed, 'default' => array(
                self::PLATFORM_IOS      => array('enabled' => 0, 'setting' => ''),
                self::PLATFORM_ANDROID  => array('enabled' => 0, 'setting' => ''),
            )),
            'commands'      => AttributeType::Mixed,
        );
    }
}
