<?php


namespace RDStation\Request;

use RDStation\Exception\IncorrectTypeException;

class Contact implements \JsonSerializable
{

    /** @var string $uuid */
    protected $uuid;

    /** @var string $name */
    protected $name;

    /** @var string email */
    protected $email;

    /** @var string $jobTitle */
    protected $jobTitle;

    /** @var string $bio */
    protected $bio;

    /** @var string $website */
    protected $website;

    /** @var string $linkedin */
    protected $linkedin;

    /** @var string $personalPhone */
    protected $personalPhone;

    /** @var string $city */
    protected $city;

    /** @var string $state */
    protected $state;

    /** @var string $country */
    protected $country;

    /** @var string $tags */
    protected $tags;

    /** @var string $extraEmails */
    protected $extraEmails;

    protected $extraFields = [];

    public function __construct(array $extraFields = [])
    {
        $this->extraFields = $extraFields;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @throws IncorrectTypeException
     */
    public function setEmail(string $email)
    {
        $this->emailValidate($email);
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getJobTitle(): string
    {
        return $this->jobTitle;
    }

    /**
     * @param string $jobTitle
     */
    public function setJobTitle(string $jobTitle)
    {
        $this->jobTitle = $jobTitle;
    }

    /**
     * @return string
     */
    public function getBio(): string
    {
        return $this->bio;
    }

    /**
     * @param string $bio
     */
    public function setBio(string $bio)
    {
        $this->bio = $bio;
    }

    /**
     * @return string
     */
    public function getWebsite(): string
    {
        return $this->website;
    }

    /**
     * @param string $website
     * @throws IncorrectTypeException
     */
    public function setWebsite(string $website)
    {
        if (!filter_var($website, FILTER_VALIDATE_URL)) {
            throw new IncorrectTypeException("not a valid website");
        }

        $this->website = $website;
    }

    /**
     * @return string
     */
    public function getLinkedin(): string
    {
        return $this->linkedin;
    }

    /**
     * @param string $linkedin
     */
    public function setLinkedin(string $linkedin)
    {
        $this->linkedin = $linkedin;
    }

    /**
     * @return string
     */
    public function getPersonalPhone(): string
    {
        return $this->personalPhone;
    }

    /**
     * @param string $personalPhone
     */
    public function setPersonalPhone(string $personalPhone)
    {
        $this->personalPhone = $personalPhone;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState(string $state)
    {
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry(string $country)
    {
        $this->country = $country;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     */
    public function setTags(array $tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return array
     */
    public function getExtraEmails(): array
    {
        return $this->extraEmails;
    }

    /**
     * @param array $extraEmails
     */
    public function setExtraEmails(array $extraEmails)
    {
        array_walk($extraEmails, [static::class, "emailValidate"]);
        $this->extraEmails = $extraEmails;
    }

    /**
     * @return string
     */
    public function getUuid() : string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     */
    public function setUuid(string  $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return array
     */
    public function getExtraFields(): array
    {
        return $this->extraFields;
    }

    /**
     * @see https://www.php.net/manual/pt_BR/jsonserializable.jsonserialize.php
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        $defaultFields = [
            'name' => $this->name,
            'email' => $this->email,
            'job_title' => $this->jobTitle,
            'bio' => $this->bio,
            'website' => $this->website,
            'linkedin' => $this->linkedin,
            'personal_phone' => $this->personalPhone,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'tags' => $this->tags,
            'extra_emails' => $this->extraEmails
        ];

        return array_merge($defaultFields, $this->extraFields);
    }

    /**
     * @param string $email
     * @throws IncorrectTypeException
     */
    protected function emailValidate(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new IncorrectTypeException("Not a valid email: " . $email);
        }
    }
}