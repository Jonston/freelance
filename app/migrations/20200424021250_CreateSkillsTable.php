<?php

use Phpmig\Migration\Migration;

class CreateSkillsTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $container = $this->getContainer();

        $sql = "CREATE TABLE `skills` (
          `id` int(11) NOT NULL,
          `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $container['db']->query($sql);

        $sql = "ALTER TABLE `skills`
            ADD PRIMARY KEY (`id`)";

        $container['db']->query($sql);

        $sql = "ALTER TABLE `skills`
            MODIFY `id` int(11) NOT NULL AUTO_INCREMENT";

        $container['db']->query($sql);
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $container = $this->getContainer();

        $sql = "SET FOREIGN_KEY_CHECKS = 0; DROP TABLE IF EXISTS `skills`; SET FOREIGN_KEY_CHECKS = 1;";

        $container['db']->query($sql);
    }
}
