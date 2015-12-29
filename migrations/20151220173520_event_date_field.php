<?php

use Phinx\Migration\AbstractMigration;

class EventDateField extends AbstractMigration
{

    public function change()
    {

        $events = $this->table('events');
        $events->addColumn('meetup_date', 'datetime', array('after' => 'supporter_id'))
            ->update();

    }


}
