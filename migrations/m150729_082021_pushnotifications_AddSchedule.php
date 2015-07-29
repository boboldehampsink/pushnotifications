<?php

namespace Craft;

/**
 * The class name is the UTC timestamp in the format of mYYMMDD_HHMMSS_pluginHandle_migrationName.
 */
class m150729_082021_pushnotifications_AddSchedule extends BaseMigration
{
    /**
     * Any migration code in here is wrapped inside of a transaction.
     *
     * @return bool
     */
    public function safeUp()
    {
        // Add schedule column
        craft()->db->createCommand()->addColumn('pushnotifications_notifications', 'schedule', ColumnType::DateTime);

        return true;
    }
}
