<?php

use Phpmig\Migration\Migration;

class CreateEmployersTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $container = $this->getContainer();

        $sql = "CREATE TABLE `employers` (
          `id` int(11) NOT NULL,
          `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
          `login` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $container['db']->query($sql);

        $sql = "ALTER TABLE `employers` ADD PRIMARY KEY (`id`)";

        $container['db']->query($sql);

        $sql = "ALTER TABLE `employers` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT";

        $container['db']->query($sql);
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $container = $this->getContainer();

        $sql = "SET FOREIGN_KEY_CHECKS = 0; DROP TABLE IF EXISTS `employers`; SET FOREIGN_KEY_CHECKS = 1;";

        $container['db']->query($sql);
    }
}
