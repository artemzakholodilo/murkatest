<?php

namespace MailerBundle\Entity;

class User
{
    private $id;

    private $name;

    private $credential;

    public function __construct()
    {
        $this->id = uniqid("user_", true);
    }

    public function getCredential()
    {
        return $this->credential;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setCredential($credential)
    {
        $this->credential = $credential;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function toJson()
    {
        $result = json_encode([
            $this->credential,
            $this->name,
            $this->id
        ]);
        if (json_last_error()) {
            trigger_error("Cannot encode json");
        }

        return $result;
    }
}