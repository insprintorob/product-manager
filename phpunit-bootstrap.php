<?php
// Define some helpful constants
define('ROOT_DIR', realpath(__DIR__)); // Makes including other stuff simpler, everything can be included from the same place
define('VIEWS_DIR', ROOT_DIR . '/views');
define('UPLOADS_DIR', ROOT_DIR . '/public/uploads');
require(ROOT_DIR . '/vendor/autoload.php');