<?php

require 'vendor/autoload.php';

use NGCSv1\Adapter\HttpAdapter;
use NGCSv1\NGCSv1;

// create an adapter with your user's API Token
// found in your CloudPanel under "Users"
$adapter = new HttpAdapter('0d5f7035afb9aadc0e19a94b46b9a5b9');

// create a ngcs object with the previous adapter
$ngcs = new NGCSv1($adapter);
