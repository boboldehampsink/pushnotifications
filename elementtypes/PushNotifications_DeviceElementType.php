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
                'label'    => Craft::t('All devices'),
            ),
        );

        foreach (craft()->pushNotifications_platforms->getAllPlatforms() as $platform) {
            $key = 'platform:'.$platform->id;

            $sources[$key] = array(
                'label'    => $platform->name,
                'criteria' => array('platformId' => $platform->id),
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
            'token'     => Craft::t('Token'),
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
            'platform'   => AttributeType::Mixed,
            'platformId' => AttributeType::Mixed,
            'token'      => AttributeType::Mixed,
            'order'      => array(AttributeType::String, 'default' => 'pushnotifications.id desc'),
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
            ->addSelect('pushnotifications_devices.platformId, pushnotifications_devices.token')
            ->join('pushnotifications_devices pushnotifications_devices', 'pushnotifications_devices.id = elements.id');

        if ($criteria->platformId) {
            $query->andWhere(DbHelper::parseParam('pushnotifications_devices.platformId', $criteria->platformId, $query->params));
        }

        if ($criteria->platform) {
            $query->join('pushnotifications_platforms pushnotifications_platforms', 'pushnotifications_platforms.id = pushnotifications.platformId');
            $query->andWhere(DbHelper::parseParam('pushnotifications_platforms.handle', $criteria->platform, $query->params));
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
