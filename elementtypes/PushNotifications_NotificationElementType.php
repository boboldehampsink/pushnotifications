<?php

namespace Craft;

/**
 * Push Notifications - Notification Element Type.
 *
 * @author    Bob Olde Hampsink
 * @copyright Copyright (c) 2015, Bob Olde Hampsink
 * @license   MIT
 *
 * @link      https://github.com/boboldehampsink
 * @since     0.0.1
 */
class PushNotifications_NotificationElementType extends BaseElementType
{
    /**
     * Returns the element type name.
     *
     * @return string
     */
    public function getName()
    {
        return Craft::t('Push Notifications');
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
                'label'    => Craft::t('All notifications'),
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
            'title'         => Craft::t('Title'),
            'body'          => Craft::t('Body'),
            'command'       => Craft::t('Command'),
            'dateCreated'   => Craft::t('Sent'),
        );
    }

    /**
     * Returns the table view HTML for a given attribute.
     *
     * @param BaseElementModel $element
     * @param string           $attribute
     *
     * @return string
     */
    public function getTableAttributeHtml(BaseElementModel $element, $attribute)
    {
        switch ($attribute) {

            case 'body':
                return strlen($element->$attribute) > 50 ? substr($element->$attribute, 0, 50).'...' : $element->$attribute;
                break;

            case 'command':
                $app = $element->getApp();
                foreach ($app->commands as $command) {
                    if ($command['param'] == $element->$attribute) {
                        return $command['name'];
                    }
                }
                break;

            default:
                return parent::getTableAttributeHtml($element, $attribute);
                break;

        }
    }

    /**
     * Defines any custom element criteria attributes for this element type.
     *
     * @return array
     */
    public function defineCriteriaAttributes()
    {
        return array(
            'app'       => AttributeType::Mixed,
            'appId'     => AttributeType::Mixed,
            'title'     => AttributeType::Name,
            'body'      => AttributeType::String,
            'command'   => AttributeType::String,
            'order'     => array(AttributeType::String, 'default' => 'pushnotifications_notifications.id desc'),
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
            ->addSelect('pushnotifications_notifications.appId, pushnotifications_notifications.title, pushnotifications_notifications.body, pushnotifications_notifications.command')
            ->join('pushnotifications_notifications pushnotifications_notifications', 'pushnotifications_notifications.id = elements.id');

        if ($criteria->appId) {
            $query->andWhere(DbHelper::parseParam('pushnotifications_notifications.appId', $criteria->appId, $query->params));
        }

        if ($criteria->app) {
            $query->join('pushnotifications_apps pushnotifications_apps', 'pushnotifications_apps.id = pushnotifications_notifications.appId');
            $query->andWhere(DbHelper::parseParam('pushnotifications_apps.handle', $criteria->app, $query->params));
        }

        if ($criteria->title) {
            $query->andWhere(DbHelper::parseParam('pushnotifications_notifications.title', $criteria->title, $query->params));
        }

        if ($criteria->body) {
            $query->andWhere(DbHelper::parseParam('pushnotifications_notifications.body', $criteria->body, $query->params));
        }

        if ($criteria->command) {
            $query->andWhere(DbHelper::parseParam('pushnotifications_notifications.command', $criteria->command, $query->params));
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
        return PushNotifications_NotificationModel::populateModel($row);
    }
}
