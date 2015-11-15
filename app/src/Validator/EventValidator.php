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
        preg_match('/^[A-Za-z0-9_:]$/', $title, $validTitle);

        if (empty($validTitle)) {
            $this->addError('Talk title can have letters, numbers, "_" and ":"');
            throw new InvalidTalkTitle('Talk title can have letters, numbers, "_" and ":"');
        }

        $description = $this->get('talk_description');
        if (strlen($description) < 20) {
            $this->addError('Talk description should have at least 20 character.');
            throw new InvalidTalkDescription('Talk description should have at least 1 character.');
        }
        preg_match('/^[A-Za-z0-9_:]$/', $description, $validDescription);

        if (empty($validDescription)) {
            $this->addError('Talk description can have letters, numbers, "_" and ":"');
            throw new InvalidTalkDescription('Talk description can have letters, numbers, "_" and ":"');
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
            $date = new \DateTime($this->get('start_date') . ' ' . $this->get('start_time'));
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