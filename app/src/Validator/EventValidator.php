<?php

namespace App\Validator;


use App\Exception\Model\Event\Entity\InvalidTalkDescription;
use App\Exception\Model\Event\Entity\InvalidTalkTitle;

class EventValidator
{

    /**
     * @var array
     */
    protected $post = [];

    protected $errors = [];

    public function __construct(array $post = [])
    {
        $this->post = $post;
    }

    protected function addError($value)
    {
        $this->errors[] = $value;
    }

    protected function get($key)
    {
        return $this->post[$key] ?? null;
    }

    public function talkValidation()
    {
        $title = $this->get('talk_title');
        if (strlen($title) < 1) {
            $this->addError('Talk title should have at least 1 character.');
            throw new InvalidTalkTitle('Talk title should have at least 1 character.');
        }

        $description = $this->get('talk_description');
        if (strlen($description) < 20) {
            $this->addError('Talk description should have at least 20 character.');
            throw new InvalidTalkDescription('Talk description should have at least 1 character.');
        }

        try {
            // placeholder for duration
            // $duration = new \DateInterval($duration);
        } catch (\Exception $e) {
            $this->addError($e->getMessage());
        }

        return $this;
    }

    public function dateValidation()
    {
        try {
            $date =  \DateTime::createFromFormat("m/d/Y H:i", $this->get('start_date') . ' ' . $this->get('start_time'));
        } catch (\Exception $e) {
            $this->addError($e->getMessage());
        }

        return $this;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return empty($this->errors);
    }

}