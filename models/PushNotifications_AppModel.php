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
     * Use the translated app name as the string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return Craft::t($this->name);
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
        );
    }
}
