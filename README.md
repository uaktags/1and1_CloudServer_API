NGCS v1 API
===========

A PHP5.4 wrapper for 1&1's New CloudServer API. Based off of DigitalOceanV2 by toin0u.

Status
------

API | Documentation | Status |
--- | ------------- | ------ |
[Servers](https://cloudpanel-api.1and1.com/documentation/v1/#_servers) | (https://github.com/uaktags/NGCSv1#servers) | [100%] |
[Images](https://cloudpanel-api.1and1.com/documentation/v1/#_images) | (https://github.com/uaktags/NGCSv1#images) | [100%]
[Shared Storages](https://cloudpanel-api.1and1.com/documentation/v1/#_shared_storages) | (https://github.com/uaktags/NGCSv1#shared_storages) | [100%] |
[Firewall Policies](https://cloudpanel-api.1and1.com/documentation/v1/#_firewall_policies) | (https://github.com/uaktags/NGCSv1#firewall_policies) | [100%] |
[PublicIPs](https://cloudpanel-api.1and1.com/documentation/v1/#_public_ips) | [0%](https://github.com/uaktags/NGCSv1#public_ips) | 
[Private Networks](https://cloudpanel-api.1and1.com/documentation/v1/#_private_networks) | [0%](https://github.com/uaktags/NGCSv1#private_networks) | 
[Monitoring Center](https://cloudpanel-api.1and1.com/documentation/v1/#_monitoring_center) | [0%](https://github.com/uaktags/NGCSv1#monitoring_center) | 
[Monitoring Policies](https://cloudpanel-api.1and1.com/documentation/v1/#_monitoring_policies) | [0%](https://github.com/uaktags/NGCSv1#monitoring_policies) | 
[Logs](https://cloudpanel-api.1and1.com/documentation/v1/#_logs) | [100%](https://github.com/uaktags/NGCSv1#logs) | 
[Users](https://cloudpanel-api.1and1.com/documentation/v1/#_users) | [100%](https://github.com/uaktags/NGCSv1#users) | 
[Usages](https://cloudpanel-api.1and1.com/documentation/v1/#_usages) | [0%](https://github.com/uaktags/NGCSv1#usages) | 
[Server Appliances](https://cloudpanel-api.1and1.com/documentation/v1/#_server_appliances) | [0%](https://github.com/uaktags/NGCSv1#server_appliances) | 
[DVD ISO](https://cloudpanel-api.1and1.com/documentation/v1/#_dvd_iso) | [0%](https://github.com/uaktags/NGCSv1#dvd_iso) | 
[Load Balancers](https://cloudpanel-api.1and1.com/documentation/v1/#_load_balancers) | [0%](https://github.com/uaktags/NGCSv1#load_balancer) | 


Installation
------------

The recommended way to install this is through [composer](http://getcomposer.org).

Run these commands to install composer, the library and its dependencies:

```bash
$ curl -sS https://getcomposer.org/installer | php
$ php composer.phar require uaktags/ngcsv1:~1.0
```

You then need to install **one** of the following:
```bash
$ php composer.phar require nategood/httpful:~0.10

```

Or edit `composer.json` and add:

```json
{
    "require": {
        "uaktags/ngcsv1": "~1.0"
    }
}
```

And then add the following:

```json
{
   "require": {
           "nategood/httpful": "0.2.19"
    },
}
```

Adapter
-------

I have added a simple HTTPFul adapter, but if you'd like to port Buzz or Guzzle as it was found in the original Digitalocean wrapper, be mindful of the API changes.

You can also build your own adapter by extending `AbstractAdapter` and implementing `AdapterInterface`.

Example
-------

```php
<?php

require 'vendor/autoload.php';

use NGCSv1\Adapter\HttpAdapter;
use NGCSv1\NGCSv1;

// create an adapter with your user's API Token
// found in your CloudPanel under "Users"
$adapter = new HttpAdapter('');

// create a ngcs object with the previous adapter
$ngcs = new NGCSv1($adapter);

// ...
```

Entities
--------

Every entity has the `getUnknownProperties` method which will return an `array` of properties set with unknown
properties by the entity. This is here only as a fail-safe until the API is fully ported.

Server
-------

```php
// ...
// initialize the Server Entity
$server = $ngcs->server();
// Get All Servers in your account
$servers = $server->getAll();
// Specify a particular server by it's ID
$aserver = $server->getById("9954B9CB401E0A8361AF73E8563FCE5F");
````

[TO BE CONTINUED]

Contributing
------------

I welcome any contributions to make this Library fully functional for all. Please keep the TODO in mind as well.

Credits
-------
[NGCSv1]
* [Tim Garrity](http://timgarrity.me)

[Original DigitalOcean PHP Library, used as base]
* [Antoine Corcy](https://twitter.com/toin0u)
* [Yassir Hannoun](https://twitter.com/yassirh)
* [Liverbool](https://github.com/liverbool)
* [All contributors](https://github.com/toin0u/DigitalOceanV2/contributors)


Contributor Code of Conduct
---------------------------

As contributors and maintainers of this project, we pledge to respect all people
who contribute through reporting issues, posting feature requests, updating
documentation, submitting pull requests or patches, and other activities.

We are committed to making participation in this project a harassment-free
experience for everyone, regardless of level of experience, gender, gender
identity and expression, sexual orientation, disability, personal appearance,
body size, race, age, or religion.

Examples of unacceptable behavior by participants include the use of sexual
language or imagery, derogatory comments or personal attacks, trolling, public
or private harassment, insults, or other unprofessional conduct.

Project maintainers have the right and responsibility to remove, edit, or reject
comments, commits, code, wiki edits, issues, and other contributions that are
not aligned to this Code of Conduct. Project maintainers who do not follow the
Code of Conduct may be removed from the project team.

Instances of abusive, harassing, or otherwise unacceptable behavior may be
reported by opening an issue or contacting one or more of the project
maintainers.

This Code of Conduct is adapted from the [Contributor
Covenant](http:contributor-covenant.org), version 1.0.0, available at
[http://contributor-covenant.org/version/1/0/0/](http://contributor-covenant.org/version/1/0/0/)

License
-------

NGCSv1 uses the same MIT License as the original project DigitalOceanV2.

DigitalOceanV2 is released under the MIT License. See the bundled
[LICENSE](https://github.com/toin0u/DigitalOceanV2/blob/master/LICENSE) file for details.