<?php

use Phinx\Seed\AbstractSeed;

class SpeakerSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * taking this from live prior to creating seeder.
     *+----+------------+-----------+------------------+----------+--------+
     *| id | first_name | last_name | email            | twitter  | avatar |
     *+----+------------+-----------+------------------+----------+--------+
     *|  1 | Rob        | Allen     | rob@akrabat.com  | @akrabat | NULL   |
     *|  2 | James      | Titcumb   | james@asgrim.com | @asgrim  | NULL   |
     *+----+------------+-----------+------------------+----------+--------+
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = array(array(
            'first_name' =>'Rob',
            'last_name'=>'Allen',
            'email'=>'rob@akrabat.com',
            'twitter'=>'@akrabat',
            'avatar'=>null
            ),
            array(
                'first_name' =>'James',
                'last_name'=>'Titcumb',
                'email'=>'james@asgrim.com',
                'twitter'=>'@asgrim',
                'avatar'=>null
            ));
        $table = $this->table('speakers');
        $table->insert($data)->save();

    }
}
