User provider from LDAP


Allow use LDAP like user provider and security in application written in Symfony2

Installation of the Bundle.
---
Simple add this line in your require in your composer.json :
```
"l3/ldap-user-bundle": "~1.0"
```
Launch the command **composer update** to install the package and add the Bundle in AppKernel.php
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

            new L3\Bundle\LdapUserBundle\L3LdapUserBundle(),
        );

        // ...
    }

    // ...
}
```

Configuration of the bundle
---
In the configuration file (parameters.yml, config.yml, config_prod.yml...), configure the ldap and the role of your users (with the cn code of the group LDAP) :
```
# Ldap
toshy62_ldap_object:
    host:     "%ldap_hostname%"
    dn:       "%ldap_dn%"
    password: "%ldap_password%"
    base_dn:  "%ldap_base_dn%"

# LdapUser
l3_ldap_user:
    roles:
        user: SPEALLPERS
        admin: DSIAPP
```
In this case, all of people in the group SPEALLUSERS obtains the ROLE_USER.
The people in group DSIAPP obtains the ROLE_ADMIN.

And configure the firewall in order to user the user provider of this Bundle :
```
# app/config/security.yml
security:
    providers:
            ldap:
                id: ldap_user_provider
```
