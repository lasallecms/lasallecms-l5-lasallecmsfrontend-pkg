<?php

/**
 *
 * Front-end package for the LaSalle Content Management System, based on the Laravel 5 Framework
 * Copyright (C) 2015  The South LaSalle Trading Corporation
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * @package    Front-end package for the LaSalle Content Management System
 * @version    1.0.0
 * @link       http://LaSalleCMS.com
 * @copyright  (c) 2015, The South LaSalle Trading Corporation
 * @license    http://www.gnu.org/licenses/gpl-3.0.html
 * @author     The South LaSalle Trading Corporation
 * @email      info@southlasalle.com
 *
 */

/**
 * ------------------------------------------
 *  Site Routes
 *  ------------------------------------------
 */

// in the event "404" is the route!
// Home
$router->get('404', [
    'as'   => '404',
    'uses' => 'TriageController@fourohfour',

]);

$router->get('503', [
    'as'   => '503',
    'uses' => 'TriageController@fiveohthree',

]);


// single post by slug, or category listing (by title)
$router->get('{slug}', 'TriageController@triage');

// Home
$router->get('/', [
   'as'   => 'home',
   'uses' => 'TriageController@home',

]);