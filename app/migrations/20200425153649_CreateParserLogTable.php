<?php

use Phpmig\Migration\Migration;

class CreateParserLogTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $container = $this->getContainer();

        $sql = "CREATE TABLE `parser_log` (
          `id` int(11) NOT NULL,
          `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
          `page` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
          `created_at` datetime NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $container['db']->query($sql);

        $sql = "ALTER TABLE `parser_log`
          ADD PRIMARY KEY (`id`)";

        $container['db']->query($sql);

        $sql = "ALTER TABLE `parser_log`
          MODIFY `id` int(11) NOT NULL AUTO_INCREMENT";

        $container['db']->query($sql);


    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $container = $this->getContainer();

        $sql = "SET FOREIGN_KEY_CHECKS = 0; DROP TABLE IF EXISTS `parser_log`; SET FOREIGN_KEY_CHECKS = 1;";

        $container['db']->query($sql);
    }
}
