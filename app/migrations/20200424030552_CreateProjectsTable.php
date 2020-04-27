<?php

use Phpmig\Migration\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $container = $this->getContainer();

        $sql = "CREATE TABLE `projects` (
          `id` int(11) NOT NULL,
          `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
          `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
          `budget` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
          `currency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
          `employer_id` int(11) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $container['db']->query($sql);

        $sql = "ALTER TABLE `projects`
                ADD PRIMARY KEY (`id`),
                ADD KEY `employer_id` (`employer_id`)";

        $container['db']->query($sql);

        $sql = "ALTER TABLE `projects`
                MODIFY `id` int(11) NOT NULL AUTO_INCREMENT";

        $container['db']->query($sql);

        $sql = "ALTER TABLE `projects` 
                ADD CONSTRAINT `projects_employer_id_foreign` FOREIGN KEY (`employer_id`) REFERENCES `employers` (`id`)";

        $container['db']->query($sql);

    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $container = $this->getContainer();

        $sql = " SET FOREIGN_KEY_CHECKS = 0; DROP TABLE IF EXISTS `projects`;  SET FOREIGN_KEY_CHECKS = 1;";

        $container['db']->query($sql);
    }
}
