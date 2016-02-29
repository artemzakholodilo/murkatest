<?php

namespace MailerBundle\Entity;

use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

class UserRepository extends EntityRepository implements UserLoaderInterface
{

    /**
     * @param string $email
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function loadUserByUsername($email)
    {
        return $this->createQueryBuilder('u')
                    ->where('u.name = :email')
                    ->setParameter('username', $email)
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    /**
     * Refreshes the user for the account interface.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     *
     * @param UserInterface $user
     *
     * @return UserInterface
     *
     * @throws UnsupportedUserException if the account is not supported
     */
    public function refreshUser(UserInterface $user)
    {
        $this->loadUserByUsername($user->getUsername());
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return $class === 'MailerBundle\Entity\User';
    }
}