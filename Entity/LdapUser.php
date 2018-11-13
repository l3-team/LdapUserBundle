<?php

namespace L3\Bundle\LdapUserBundle\Entity;

use OpenLdapObject\Entity;
use OpenLdapObject\Annotations as OLO;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @OLO\Dn(value="ou=people")
 * @OLO\Entity({"inetOrgPerson"})
 */
class LdapUser extends Entity implements UserInterface {
    /**
     * @OLO\Column(type="string")
     * @OLO\Index
     */
    protected $uid;

    /**
     * @OLO\Column(type="array")
     */
    protected $cn;

    /**
     * @OLO\Column(type="array")
     */
    protected $sn;

    /**
     * @OLO\Column(type="string")
     */
    protected $givenName;

    /**
     * @OLO\Column(type="string")
     */
    protected $mail;

    /**
     * @OLO\Column(type="array")
     */
    protected $memberOf;
    
    /**
     * @OLO\Column(type="string")
     */
    protected $eduPersonPrimaryAffiliation;

    /**
     * @OLO\Column(type="string")
     */
    protected $displayName;

    /**
     * @OLO\Column(type="array")
     */
    protected $eduPersonAffiliation;

    /**
     * Liste des roles généré depuis la configuration de l'authentification
     * @var array
     */
    private $roles = array();

    public function updateRoles(array $rolesConfig = array()) {
        /*
         * Si aucune configuration est défini
         */
        if(count($rolesConfig) < 1) {
            $this->roles = array('ROLE_USER');
            return;
        }

        // Initialisation des roles
        $this->roles = array();

        // Récupération des groupes de l'utilisateur
        $groups = $this->getMemberOf();
        // Création d'un tableau vide pour lister les CN des groupes
        $groupsCn = array();

        // Récupération des CN des différents groupes
        foreach($groups as $dn) {
            $groupsCn[] = substr($dn, 3, strpos($dn, ',', 3)-3); // Récupère le CN à partir du DN du groupe
        }

        // Vérification role par role que le groupe est bien présent
        foreach($rolesConfig as $role => $group) {
            if(in_array($group, $groupsCn)) {
                $this->roles[] = 'ROLE_' . strtoupper($role);
            }
        }
    }

    /**
     * Returns the roles granted to the user.
     *
     * @return Role[] The user roles
     */
    public function getRoles() {
        return $this->roles;
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword() {
        return null;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt() {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername() {
        return $this->getFirstCn();
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials() {

    }

    public function equals(UserInterface $user) {
        if(!$user instanceof People) {
            return false;
        }

        if($user->getUid() !== $this->getUid()) {
            return false;
        }

        return true;
    }

    public function getFirstCn() {
        return $this->cn[0];
    }

    /*
     * En dessous: getter et setter automatiquement généré par OpenLdapObject => Ne pas modifier si possible
     */

    public function getUid() {
        return $this->uid;
    }

    public function setUid($value) {
        $this->uid = $value;
        return $this;
    }

    public function getCn() {
        return $this->cn;
    }

    public function addCn($value) {
        $this->cn->add($value);
        return $this;
    }

    public function removeCn($value) {
        $this->cn->removeElement($value);
        return $this;
    }

    public function getSn() {
        return $this->sn;
    }

    public function addSn($value) {
        $this->sn->add($value);
        return $this;
    }

    public function removeSn($value) {
        $this->sn->removeElement($value);
        return $this;
    }

    public function getGivenName() {
        return $this->givenName;
    }

    public function setGivenName($value) {
        $this->givenName = $value;
        return $this;
    }

    public function getMail() {
        return $this->mail;
    }

    public function setMail($value) {
        $this->mail = $value;
        return $this;
    }

    public function addMemberOf($value) {
        $this->memberOf->add($value);
        return $this;
    }

    public function removeMemberOf($value) {
        $this->memberOf->removeElement($value);
        return $this;
    }

    public function getMemberOf() {
        return $this->memberOf;
    }

    public function getEduPersonPrimaryAffiliation() {
        return $this->eduPersonPrimaryAffiliation;
    }

    public function setEduPersonPrimaryAffiliation($value) {
        $this->eduPersonPrimaryAffiliation = $value;
        return $this;
    }

    public function getDisplayName() {
        return $this->displayName;
    }

    public function setDisplayName($value) {
        $this->displayName = $value;
        return $this;
    }

    public function getEduPersonAffiliation() {
        return $this->eduPersonAffiliation;
    }

    public function addEduPersonAffiliation($value) {
        $this->eduPersonAffiliation->add($value);
        return $this;
    }

    public function removeEduPersonAffiliation($value) {
        $this->eduPersonAffiliation->removeElement($value);
        return $this;
    }

}
?>
