Symfony 2/3/4 User provider from LDAP

(author : Universite Lille)


Allow use LDAP like user provider and security in application written in Symfony2/3/4

This bundle is also able for LDAP Entities through Doctrine ORM.
You can make your own entities with Doctrine, just simply take example on the LdapUser.php file in this bundle.

Installation of the Bundle.
---
Simple add this line in your require in your composer.json :
```
"l3/ldap-user-bundle": "~1.0"
```
Launch the command **composer update** to install the package

For Symfony 2 and 3 :
add the Bundle in AppKernel.php
```
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new OpenLdapObject\Bundle\LdapObjectBundle\OpenLdapObjectLdapObjectBundle(),
            new L3\Bundle\LdapUserBundle\L3LdapUserBundle(),
        );

        // ...
    }

    // ...
}
```

For Symfony 4 :
Verify if the lines are present in config/bundles.php file (if not present, just add the lines) :
```
# config/bundles.php
...
L3\Bundle\LdapUserBundle\L3LdapUserBundle::class => ['all' => true],
OpenLdapObject\Bundle\LdapObjectBundle\OpenLdapObjectLdapObjectBundle::class => ['all' => true],
...
```

Configuration of the bundle
---
For Symfony 2 and Symfony 3 :
in the configuration file app/config/parameters.yml.dist and app/config/parameters.yml, add this under parameters:
```
# app/config/parameters.yml.dist
# app/config/parameters.yml
...
parameters:
    ldap_hostname: ldap.univ.fr				# the ldap host of your server ldap
    ldap_base_dn: 'dc=univ,dc=fr'			# the base dn of your server ldap which contains the users
    ldap_dn: 'uid=login,ou=ldapusers,dc=univ,dc=fr'	# the login of your server ldap
    ldap_password: password				# the password of your server ldap
...
```
and configure the values in parameters.yml file.

next in the configuration file app/config/config.yml, add this lines at the end of the file :
```
# app/config/config.yml
...
# Ldap
open_ldap_object_ldap_object:
    host:     "%ldap_hostname%"
    dn:       "%ldap_dn%"
    password: "%ldap_password%"
    base_dn:  "%ldap_base_dn%"
```

(optional) you can affect automatically a specific role to a user if the user got the ldap group in his memberOf ldap field.
add this at the end of the file app/config/config.yml :
```
# app/config/config.yml
...
# LdapUser
l3_ldap_user:
    roles:
        user: SPEALLPERS		# if the user got the group SPEALLPERS in this memberOf ldap field, he obtains automatically the role "ROLE_USER"
        admin: DSIAPP			# if the user got the group DSIAPP in this memberOf ldap field, he obtains automatically the role "ROLE_ADMIN"
```

And configure the firewall in order to use the user provider of this Bundle :
```
# app/config/security.yml
...
security:
    providers:
            ldap:
                id: ldap_user_provider
```

For Symfony 4 :
in the configuration file .env.dist and .env, add this :
```
# .env.dist 
# .env
...
###> l3/ldap-user-bundle ###
LDAP_HOSTNAME=ldap.univ.fr
LDAP_BASE_DN=dc=univ,dc=fr
LDAP_DN=cn=login,dc=univ,dc=fr
LDAP_PASSWORD=password
###< l3/ldap-user-bundle ###
...
```
and configure the values in the file .env

next add this lines in the config/services.yaml file (under parameters) :
```
# config/services.yaml
...
parameters:
...
    ldap_hostname: '%env(string:LDAP_HOSTNAME)%'
    ldap_base_dn: '%env(string:LDAP_BASE_DN)%'
    ldap_dn: '%env(string:LDAP_DN)%'
    ldap_password: '%env(string:LDAP_PASSWORD)%'
...
```

next in the configuration file config/services.yaml, add this lines at the end of the file :
```
# config/services.yaml

# Ldap
open_ldap_object_ldap_object:
    host:     "%ldap_hostname%"
    dn:       "%ldap_dn%"
    password: "%ldap_password%"
    base_dn:  "%ldap_base_dn%"
```

(optional) you can affect automatically a specific role to a user if the user got the ldap group in his memberOf ldap field.
add this at the end of the file config/services.yaml :
```
# config/services.yaml
...
# LdapUser
l3_ldap_user:
    roles:
        user: SPEALLPERS                # if the user got the group SPEALLPERS in this memberOf ldap field, he obtains automatically the role "ROLE_USER"
        admin: DSIAPP                   # if the user got the group DSIAPP in this memberOf ldap field, he obtains automatically the role "ROLE_ADMIN"
...
```

And configure the firewall in order to user the user provider of this Bundle :
```
# config/packages/security.yaml
security:
    providers:
            ldap:
                id: ldap_user_provider
```


Twig page for control if the user is present in the ldap group of the ROLE_USER
---
you can show a page twig if the user is not present in the ldap group of the ROLE_USER,
just create the file app/Resources/TwigBundle/views/Exception/error.html.twig and add this :
```
{% extends '::base.html.twig' %}

{% block title %}
    Error
{% endblock %}

{% block body %}
    {% set role_user = 'ROLE_USER' %}
    {% if status_code == 500 and app.user is not null and role_user not in app.user.roles %}
        <h2>You are not authorized to access to this application.</h2>
    {% elseif status_code == 404 %}
        <h2>Page not found.</h2>
    {% else %}
        <h2>The application returns an error "{{ status_code }} {{ status_text }}".</h2>
    {% endif %}
{% endblock %}
```
