<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE OR REPLACE VIEW v_tickets AS
            SELECT 
        `t`.`id` AS `id`,
        `t`.`assignee` AS `assignee_id`,
        `t`.`status` AS `status_id`,
        `t`.`expiration` AS `expiration`,
        `t`.`created_at` AS `created_at`,
        `p`.`name` AS `priority_name`,
        `s`.`name` AS `status_name`,
        `g`.`name` AS `ticket_group_name`,
        `g`.`id` AS `ticket_group_id`,
        IF((`t`.`store_type` = 'App\\\Store'),
            (SELECT 
                    `homestead`.`stores`.`store_name`
                FROM
                    `homestead`.`stores`
                WHERE
                    (`homestead`.`stores`.`id` = `t`.`store_id`)),
            (SELECT 
                    `homestead`.`m_stores`.`store_name`
                FROM
                    `homestead`.`m_stores`
                WHERE
                    (`homestead`.`m_stores`.`id` = `t`.`store_id`))) AS `store_name`,
        `i`.`subject` AS `subject`,
        `i`.`details` AS `details`,
        `i`.`catB` AS `catB`,
        `homestead`.`category_b`.`name` AS `catB_name`,
        CONCAT_WS(' ',
                `asgn`.`fName`,
                `asgn`.`mName`,
                `asgn`.`lName`) AS `assigned_user`,
        CONCAT_WS(' ',
                `lger`.`fName`,
                `lger`.`mName`,
                `lger`.`lName`) AS `logger`,
        `c`.`name` AS `category`,
        IFNULL(`tickets_extend_count`.`times_extended`,
                0) AS `times_extended`
    FROM
        (((((((((`homestead`.`tickets` `t`
        JOIN `homestead`.`priorities` `p` ON ((`t`.`priority` = `p`.`id`)))
        JOIN `homestead`.`ticket_status` `s` ON ((`t`.`status` = `s`.`id`)))
        JOIN `homestead`.`ticket_groups` `g` ON ((`t`.`group` = `g`.`id`)))
        JOIN `homestead`.`incidents` `i` ON ((`t`.`issue_id` = `i`.`id`)))
        LEFT JOIN `homestead`.`users` `asgn` ON ((`t`.`assignee` = `asgn`.`id`)))
        JOIN `homestead`.`users` `lger` ON ((`t`.`logged_by` = `lger`.`id`)))
        LEFT JOIN `homestead`.`categories` `c` ON ((`i`.`category` = `c`.`id`)))
        LEFT JOIN `homestead`.`category_b` ON ((`i`.`catB` = `homestead`.`category_b`.`id`)))
        LEFT JOIN (SELECT 
            `homestead`.`extends`.`ticket_id` AS `ticket_id`,
                COUNT(`homestead`.`extends`.`ticket_id`) AS `times_extended`
        FROM
            `homestead`.`extends`
        GROUP BY `homestead`.`extends`.`ticket_id`) `tickets_extend_count` ON ((`t`.`id` = `tickets_extend_count`.`ticket_id`)))
    WHERE
        ISNULL(`t`.`deleted_at`);");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW v_tickets');
    }
}
