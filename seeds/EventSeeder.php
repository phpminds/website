<?php

use Phinx\Seed\AbstractSeed;

class EventSeeder extends AbstractSeed
{

    public function run()
    {
        $data = array(
            array(
                'meetup_id'    => '226158970',
                'meetup_venue_id' =>'24159763',
                'joindin_event_name'=>'PHPMiNDS December 2015',
                'joindin_talk_id'=>'16610',
                'joindin_url'=>'https://m.joind.in/talk/view/16610',
                'speaker_id'=>'1',
                'supporter_id'=>'1',
                'meetup_date'=>date('Y-m-d H:i:s',strtotime('2015-12-17 19:00:00'))
            )
        );
        $events = $this->table('events');
        $events->insert($data)
            ->save();

    }
}
