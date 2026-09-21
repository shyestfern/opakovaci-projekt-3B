<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Main::index');
$routes->get('etapy/(:num)', 'Main::etapy/$1');
