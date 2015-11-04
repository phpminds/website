<?php

use Phinx\Migration\AbstractMigration;

class Sponsors extends AbstractMigration
{
    public function up()
    {
        $users = $this->table('sponsors');
        $users
            ->addColumn('name', 'string', ['limit' => 60])
            ->addColumn('url', 'string', ['limit' => 253])
            ->addColumn('twitter', 'string', ['limit' => 15])
            ->addColumn('email', 'string', ['limit' => 254])
            ->addColumn('logo', 'string', ['limit' => 250])
            ->addIndex(['twitter'], ['unique' => true])
            ->save();
    }
}
