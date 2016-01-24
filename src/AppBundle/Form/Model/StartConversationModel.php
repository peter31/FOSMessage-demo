<?php

namespace AppBundle\Form\Model;

use AppBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class StartConversationModel
{
    /**
     * @var User[]
     */
    private $recipients;

    /**
     * @var string|null
     */
    private $subject;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    private $body;

    /**
     * @return \AppBundle\Entity\User[]
     */
    public function getRecipients()
    {
        return $this->recipients;
    }

    /**
     * @param \AppBundle\Entity\User[] $recipients
     */
    public function setRecipients($recipients)
    {
        \Webmozart\Assert\Assert::allIsInstanceOf($recipients, User::class);
        \Webmozart\Assert\Assert::notEmpty($recipients);

        $this->recipients = $recipients;
    }

    /**
     * @return null|string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param null|string $subject
     */
    public function setSubject($subject)
    {
        \Webmozart\Assert\Assert::nullOrString($subject);

        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        \Webmozart\Assert\Assert::string($body);
        \Webmozart\Assert\Assert::notEmpty($body);

        $this->body = $body;
    }
}
