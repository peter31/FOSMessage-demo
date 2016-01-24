<?php

namespace AppBundle\Form\DataTransformer;

use FOS\UserBundle\Form\DataTransformer\UserToUsernameTransformer;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

/**
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class RecipientsTransformer implements DataTransformerInterface
{
    /**
     * @var UserToUsernameTransformer
     */
    private $userToUsernameTransformer;

    /**
     * @param UserToUsernameTransformer $userToUsernameTransformer
     */
    public function __construct(UserToUsernameTransformer $userToUsernameTransformer)
    {
        $this->userToUsernameTransformer = $userToUsernameTransformer;
    }

    /**
     * Transforms a collection of UserInterface instances into a list of usernames.
     *
     * @param UserInterface[] $value UserInterface instances
     *
     * @return string|null Usernames
     *
     * @throws UnexpectedTypeException if one of the given value is not a UserInterface instance
     */
    public function transform($value)
    {
        if (null === $value) {
            return null;
        }

        if (!is_array($value) && !$value instanceof \Traversable) {
            throw new UnexpectedTypeException($value, 'array or Traversable');
        }

        $usernames = [];

        foreach ($value as $user) {
            $usernames[] = $this->userToUsernameTransformer->transform($user);
        }

        return implode(', ', $usernames);
    }

    /**
     * Transforms a list of usernames into an array of UserInterface instances.
     *
     * @param string $value Usernames list
     *
     * @return UserInterface[] the corresponding UserInterface instances
     *
     * @throws UnexpectedTypeException if the given value is not a string
     */
    public function reverseTransform($value)
    {
        if (null === $value || '' === $value) {
            return null;
        }

        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $usernames = explode(',', $value);
        $users = [];

        foreach ($usernames as $username) {
            $user = $this->userToUsernameTransformer->reverseTransform(trim($username));

            if ($user) {
                $users[] = $user;
            }
        }

        return $users;
    }
}
