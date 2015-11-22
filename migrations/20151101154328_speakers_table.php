<?php

use Phinx\Migration\AbstractMigration;

class SpeakersTable extends AbstractMigration
{
    public function up()
    {
        $users = $this->table('speakers');
        $users
            ->addColumn('first_name', 'string', ['limit' => 60])
            ->addColumn('last_name', 'string', ['limit' => 60])
            ->addColumn('email', 'string', ['limit' => 254])
            ->addColumn('twitter', 'string', ['limit' => 15])
            ->addColumn('avatar', 'text', ['null' => true])
            ->addIndex(['twitter', 'email'], ['unique' => true])
            ->save();
    }
}
