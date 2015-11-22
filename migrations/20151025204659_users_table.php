<?php

use Phinx\Migration\AbstractMigration;

class UsersTable extends AbstractMigration
{

    public function up()
    {
        $users = $this->table('users');
        $users->addColumn('email', 'string', ['limit' => 120])
            ->addColumn('password', 'char', ['limit' => 60])
            ->addColumn('role', 'integer', ['limit' => 1])
            ->addColumn('status', 'integer', ['limit' => 1])
            ->addIndex(['email'], ['unique' => true])
            ->addIndex(['status'])
            ->save();
    }
}
