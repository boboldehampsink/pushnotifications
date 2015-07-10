<?php

namespace Craft;

/**
 * Push Notifications - Device Element Type.
 *
 * @author    Bob Olde Hampsink
 * @copyright Copyright (c) 2015, Bob Olde Hampsink
 * @license   MIT
 *
 * @link      https://github.com/boboldehampsink
 * @since     0.0.1
 */
class PushNotifications_DeviceElementType extends BaseElementType
{
    /**
     * Returns the element type name.
     *
     * @return string
     */
    public function getName()
    {
        return Craft::t('Push Notifications - Devices');
    }

    /**
     * Returns whether this element type has content.
     *
     * @return bool
     */
    public function hasContent()
    {
        return false;
    }

    /**
     * Returns whether this element type has titles.
     *
     * @return bool
     */
    public function hasTitles()
    {
        return false;
    }

    /**
     * Returns this element type's sources.
     *
     * @param string|null $context
     *
     * @return array|false
     */
    public function getSources($context = null)
    {
        $sources = array(
            '*' => array(
                'label'    => Craft::t('All apps'),
            ),
        );

        foreach (craft()->pushNotifications_apps->getAllApps() as $app) {
            $key = 'app:'.$app->id;

            $sources[$key] = array(
                'label'    => $app->name,
                'criteria' => array('appId' => $app->id),
            );
        }

        return $sources;
    }

    /**
     * Returns the attributes that can be shown/sorted by in table views.
     *
     * @param string|null $source
     *
     * @return array
     */
    public function defineTableAttributes($source = null)
    {
        return array(
            'token'       => Craft::t('Token'),
            'dateCreated' => Craft::t('Added'),
        );
    }

    /**
     * Defines any custom element criteria attributes for this element type.
     *
     * @return array
     */
    public function defineCriteriaAttributes()
    {
        return array(
            'app'        => AttributeType::Mixed,
            'appId'      => AttributeType::Mixed,
            'platform'   => AttributeType::Mixed,
            'token'      => AttributeType::Mixed,
            'order'      => array(AttributeType::String, 'default' => 'pushnotifications_devices.id desc'),
        );
    }

    /**
     * Modifies an element query targeting elements of this type.
     *
     * @param DbCommand            $query
     * @param ElementCriteriaModel $criteria
     *
     * @return mixed
     */
    public function modifyElementsQuery(DbCommand $query, ElementCriteriaModel $criteria)
    {
        $query
            ->addSelect('pushnotifications_devices.appId, pushnotifications_devices.platform, pushnotifications_devices.token')
            ->join('pushnotifications_devices pushnotifications_devices', 'pushnotifications_devices.id = elements.id');

        if ($criteria->appId) {
            $query->andWhere(DbHelper::parseParam('pushnotifications_devices.appId', $criteria->appId, $query->params));
        }

        if ($criteria->app) {
            $query->join('pushnotifications_apps pushnotifications_apps', 'pushnotifications_apps.id = pushnotifications_devices.appId');
            $query->andWhere(DbHelper::parseParam('pushnotifications_apps.handle', $criteria->app, $query->params));
        }

        if ($criteria->platform) {
            $query->andWhere(DbHelper::parseParam('pushnotifications_devices.platform', $criteria->platform, $query->params));
        }

        if ($criteria->token) {
            $query->andWhere(DbHelper::parseParam('pushnotifications_devices.token', $criteria->token, $query->params));
        }
    }

    /**
     * Populates an element model based on a query result.
     *
     * @param array $row
     *
     * @return array
     */
    public function populateElementModel($row)
    {
        return PushNotifications_DeviceModel::populateModel($row);
    }
}
