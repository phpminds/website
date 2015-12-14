<?php

use Phinx\Migration\AbstractMigration;

class AddEventNameField extends AbstractMigration
{
    public function up()
    {
        $events = $this->table('events');
        $events->addColumn('joindin_event_name', 'string', ['limit' => 60, 'after' => 'meetup_venue_id'])
            ->save();
    }
}
