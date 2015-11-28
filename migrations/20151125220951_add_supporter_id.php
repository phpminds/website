<?php

use Phinx\Migration\AbstractMigration;

class AddSupporterId extends AbstractMigration
{
    public function up()
    {
        $events = $this->table('events');
        $events->renameColumn('sponsor_id', 'supporter_id');
    }
}
