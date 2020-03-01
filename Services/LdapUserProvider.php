<?php

namespace L3\Bundle\LdapUserBundle\Services;

use L3\Bundle\LdapUserBundle\Entity\LdapUser;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use OpenLdapObject\Bundle\LdapObjectBundle\LdapWrapper;

class LdapUserProvider implements UserProviderInterface {
    /**
     * @var LdapWrapper
     */
    private $em;

    /**
     * Configuration de ldap_user.roles
     * @var array
     */
    private $rolesConfig;


    public function __construct(LdapWrapper $em, array $rolesConfig) {
        $this->em = $em;
        $this->rolesConfig = $rolesConfig;
    }

    public function loadUserByUsername($username) {
        $user = $this->em->getRepository('L3\Bundle\LdapUserBundle\Entity\LdapUser')->find($username);

		if(!$user && $username === '__NO_USER__') {
			$user = new LdapUser();
			$user->setUid('__NO_USER__');
			$user->addCn('Anonyme');
			$user->addSn('Anonyme');
			$user->addMemberOf('cn=anon,dc=univ-lille3,dc=fr');
		} elseif(!$user) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }

        $user->updateRoles(array_merge($this->rolesConfig, array('anon' => 'ROLE_ANON')));

        return $user;
    }

    public function refreshUser(UserInterface $user) {
        if(!$user instanceof LdapUser) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUid());
    }

    public function supportsClass($class) {
        return LdapUser::class === $class;
    }
} 
