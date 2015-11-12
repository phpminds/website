<?php

use Phinx\Migration\AbstractMigration;

class Events extends AbstractMigration
{
    public function up()
    {
        $users = $this->table('events');
        $users
            ->addColumn('meetup_id', 'integer')
            ->addColumn('meetup_venue_id', 'integer')
            ->addColumn('joindin_talk_id', 'integer')
            ->addColumn('joindin_url', 'string', ['limit' => 253])
            ->addColumn('speaker_id', 'integer')
            ->addColumn('sponsor_id', 'integer')
            ->addIndex(['meetup_id', 'speaker_id'])
            ->save();
    }
}
