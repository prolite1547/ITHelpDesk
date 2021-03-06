<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        DB::table('departments')->insert([
            ['department' => 'IT'],
            ['department' => 'Accounting'],
            ['department' => 'Engineering']
        ]);

        DB::table('positions')->insert([
            ['position' => 'Web Developer','department_id' => 1],
            ['position' => 'Accountant','department_id' => 2],
            ['position' => 'Surveyor','department_id' => 3],
            ['position' => 'Support','department_id' => 1],
            ['position' => 'Technical','department_id' => 1]
        ]);

        DB::table('roles')->insert([
            ['role' => '1st Level Support'],
            ['role' => 'Tower'],
            ['role' => 'User'],
            ['role' => 'Admin']
        ]);


        DB::table('category_groups')->insert([
            ['group_name' => 'Ticket'],
            ['group_name' => 'Priority'],
            ['group_name' => 'IncCat'],
            ['group_name' => 'IncCatA'],
            ['group_name' => 'IncStatus'],
            ['group_name' => 'NumberType'],
            ['group_name' => 'No Value'],
            ['group_name' => 'Resolve'],
        ]);

        DB::table('categories')->insert([
            ['value'=>'inc', 'name' => 'Incident', 'group' => 1,'order' => 1],
            ['value'=>'req', 'name' => 'Request', 'group' => 1,'order' => 1],
            ['value'=>'low', 'name' => 'Low', 'group' => 2,'order' => 1],
            ['value'=>'nrml', 'name' => 'Normal', 'group' => 2,'order' => 2],
            ['value'=>'high', 'name' => 'High', 'group' => 2,'order' => 3],
            ['value'=>'urgt', 'name' => 'Urgent', 'group' => 2,'order' => 4],
            ['value'=>'hrd', 'name' => 'Hardware', 'group' => 3,'order' => 1],
            ['value'=>'sft', 'name' => 'Software', 'group' => 3,'order' => 1],
            ['value'=>'POShrd', 'name' => 'POS Hardware', 'group' => 4,'order' => 1],
            ['value'=>'POSsft', 'name' => 'POS Software', 'group' => 4,'order' => 1],
            ['value'=>'opn', 'name' => 'Open', 'group' => 5,'order' => 1],
            ['value'=>'ong', 'name' => 'Ongoing', 'group' => 5,'order' => 1],
            ['value'=>'cls', 'name' => 'Closed', 'group' => 5,'order' => 1],
            ['value'=>'tel', 'name' => 'Telephone', 'group' => 6,'order' => 1],
            ['value'=>'cell', 'name' => 'Cell', 'group' => 6,'order' => 1],
            ['value'=>'null', 'name' => '', 'group' => 7,'order' => 1],
            ['value'=>'res', 'name' => 'restart', 'group' => 8,'order' => 1],
            ['value'=>'dis', 'name' => 'dispose', 'group' => 8,'order' => 1],
            ['value'=>'grant', 'name' => 'grant', 'group' => 8,'order' => 1],
        ]);



        DB::table('stores')->insert([
            ['store_name' => 'Bajada'],
            ['store_name' => 'Liloan'],
            ['store_name' => 'Matina'],
            ['store_name' => 'Naval'],
            ['store_name' => 'Oton']
        ]);

        $this->call([
            ProfPicTableSeeder::class,
            ContactsTableSeeder::class,
            CallersTableSeeder::class,
//            TicketTableSeeder::class,
//            MessageTableSeeder::class
        ]);

    }
}
