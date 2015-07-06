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
            'title'     => Craft::t('Title'),
            'body'      => Craft::t('Body'),
            'param'     => Craft::t('Param'),
        );
    }

    /** Returns the table view HTML for a given attribute.
     * @param BaseElementModel $element
     * @param string           $attribute
     *
     * @return string
     */
    public function getTableAttributeHtml(BaseElementModel $element, $attribute)
    {
        // Don't show full body text in element index table
        if ($attribute == 'body') {
            return strlen($element->$attribute) > 50 ? substr($element->$attribute, 0, 50).'...' : $element->$attribute;
        }

        return parent::getTableAttributeHtml($element, $attribute);
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
            'param'     => AttributeType::String,
            'order'     => array(AttributeType::String, 'default' => 'pushnotifications.id desc'),
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
            ->addSelect('pushnotifications_notifications.appId, pushnotifications_notifications.title, pushnotifications_notifications.body, pushnotifications_notifications.param')
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

        if ($criteria->param) {
            $query->andWhere(DbHelper::parseParam('pushnotifications_notifications.param', $criteria->param, $query->params));
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

    /**
     * Returns the HTML for an editor HUD for the given element.
     *
     * @param BaseElementModel $element
     *
     * @return string
     */
    public function getEditorHtml(BaseElementModel $element)
    {
        // Title/body/param fields
        $html = craft()->templates->render('pushnotifications/notifications/_edit', array(
            'notification' => $element,
        ));

        // Everything else
        $html .= parent::getEditorHtml($element);

        return $html;
    }
}
