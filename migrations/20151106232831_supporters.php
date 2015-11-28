<?php

use Phinx\Migration\AbstractMigration;

class Supporters extends AbstractMigration
{
    public function up()
    {
        if ($this->hasTable('sponsors')) {
            $this->dropTable('sponsors');
        }

        $users = $this->table('supporters');
        $users
            ->addColumn('name', 'string', ['limit' => 60])
            ->addColumn('url', 'string', ['limit' => 253])
            ->addColumn('twitter', 'string', ['limit' => 15])
            ->addColumn('email', 'string', ['limit' => 254])
            ->addColumn('logo', 'string', ['limit' => 250, 'null' => true])
            ->addIndex(['twitter'], ['unique' => true])
            ->save();
    }
}
