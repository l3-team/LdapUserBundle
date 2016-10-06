<?php

namespace L3\Bundle\LdapUserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class SecurityController extends Controller {
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction() {
        $roles = array();
        foreach($this->container->getParameter('ldap_user.roles') as $key => $value) {
            $roles[] = array('cn' => $value, 'role' => $key);
        }
        return $this->render('L3LdapUserBundle:Default:index.html.twig', array('user' => $this->getUser(), 'roles' => $roles));
    }
}
