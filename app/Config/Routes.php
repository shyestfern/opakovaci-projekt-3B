<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Main::index');
$routes->get('etapy/(:num)', 'Main::etapy/$1');
$routes->get('poradi/(:num)/(:num)', 'Main::poradi/$1/$2');
