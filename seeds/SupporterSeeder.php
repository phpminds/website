<?php

use Phinx\Seed\AbstractSeed;

class SupporterSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * +----+--------------------+---------------------+----------+------------------------+------+
     * | id | name               | url                 | twitter  | email                  | logo |
     * +----+--------------------+---------------------+----------+------------------------+------+
     * |  1 | PHPMinds Organiser | http://phpminds.org | phpminds | phpminds.org@gmail.com | NULL |
     * +----+--------------------+---------------------+----------+------------------------+------+
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = array(array(
            'name' =>'PHPMinds Organiser',
            'url'=>'http://phpminds.org',
            'email'=>'phpminds.org@gmail.com',
            'twitter'=>'@phpminds',
            'logo'=>null
        ));
        $table = $this->table('supporters');
        $table->insert($data)->save();

    }
}
