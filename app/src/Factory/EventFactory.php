<?php

namespace App\Factory;

use App\Model\Event\Entity\Talk;

class EventFactory
{
    public static function getByRequest($request, $speaker, $venue, $supporter, $title, $description)
    {
        $event = new \App\Model\Event\Event(
            new Talk(
                strip_tags($request->getParam('talk_title'), '<p><a><br>'),
                strip_tags($request->getParam('talk_description'), '<p><img><a><br>'),
                $speaker
            ),
            $request->getParam('start_date'),
            $request->getParam('start_time') < 10 ? '0' . $request->getParam('start_time') :  $request->getParam('start_time'),
            $venue,
            $supporter
        );

        $event->setName($title);
        $event->setDescription($description);

        return $event;
    }
}