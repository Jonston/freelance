<?php

use Phpmig\Migration\Migration;

class CreateProjectsSkillsTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $container = $this->getContainer();

        $sql = "CREATE TABLE `projects_skills` (
          `project_id` int(11) NOT NULL,
          `skill_id` int(11) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $container['db']->query($sql);

        $sql = "ALTER TABLE `projects_skills`
          ADD KEY `skill_id` (`skill_id`),
          ADD KEY `project_id` (`project_id`)";

        $container['db']->query($sql);

        $sql = "ALTER TABLE `projects_skills`
          ADD CONSTRAINT `projects_skills_foreign_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
          ADD CONSTRAINT `projects_skills_foreign_2` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE";

        $container['db']->query($sql);

    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $container = $this->getContainer();

        $sql = " SET FOREIGN_KEY_CHECKS = 0; DROP TABLE IF EXISTS `projects_skills`;  SET FOREIGN_KEY_CHECKS = 1;";

        $container['db']->query($sql);
    }
}
