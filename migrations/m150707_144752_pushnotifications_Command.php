<?php

namespace Craft;

/**
 * The class name is the UTC timestamp in the format of mYYMMDD_HHMMSS_pluginHandle_migrationName.
 */
class m150707_144752_pushnotifications_Command extends BaseMigration
{
    /**
     * Any migration code in here is wrapped inside of a transaction.
     *
     * @return bool
     */
    public function safeUp()
    {
        // Rename param to command
        craft()->db->createCommand()->renameColumn('pushnotifications_notifications', 'param', 'command');

        // Add commands to app record
        craft()->db->createCommand()->addColumn('pushnotifications_apps', 'commands', ColumnType::Text);

        return true;
    }
}
