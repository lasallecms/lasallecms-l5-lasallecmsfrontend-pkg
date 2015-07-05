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

/*
	|--------------------------------------------------------------------------
	| IMPORTANT: YOUR FRONT END TEMPLATE RESIDES IN YOUR APP, *NOT* IN THIS PKG!
	|--------------------------------------------------------------------------
    |
    | Sure, I put the admin template in my "lasallecmsadmin" package, because who
    | conjures up their own admin template?
    |
    | OTOH, everyone has their own unique frontend template. So I assume your
    | frontend template resides in your app.
    |
    | ======>   **** SO YOU NEED TO DO SOMETHING VERY IMPORTANT ****    <=========
    |
    | YOU NEED TO MAKE SURE THAT THE PATH TO THE FRONT END TEMPLATE IS ONE OF THE
    | ARRAY ELEMENTS IN THE view CONFIGURATION FILE'S "paths" ARRAY. THE FILE IS
    | LOCATED AT "config/view.php" IN YOUR APP (ie, not in a package!).
    |
*/

/*
	|--------------------------------------------------------------------------
	| IMPORTANT: PAGE OR POST?  IOW: BLADE FILE OR DATABASE?
	|--------------------------------------------------------------------------
    |
    | Do you want to handcraft your web page; or, do you want to input it into
    | database (as a post)?
    |
    | Either way: CREATE THE POST CATEGORY! Each page needs a post category.
    |
    | Of course, either way, you need a blade file.
    |
    | For example, a main menu has "Blog", "About", "Our Team".
    | So you will have post categories "Blog", "About", "Team".
    | When you click the "About" link (or one of the others), this package,
    | which has that route,looks for all posts associated with the "About" category.
    |
    | ======>   **** IMPORTANT!! ****    <=========
    | If one or more are posts are found in the database for that category,then the database posts are pushed to the view.
    | If no posts are found for that category,then no posts are pushed to the view.
    |
*/

/*
	|--------------------------------------------------------------------------
	| IMPORTANT: YOU MUST SET UP THE FRONT-END VIEWS IN YOUR APP IN A SPECIFIC FOLDER STRUCTURE
	|--------------------------------------------------------------------------
    |
    | The views in your app for your front-end must be set-up in this folder structure:
    |
    | "Root folder" = base_path().'/resource/views/'.Template_Name
    |
    | Using the default template name "lasalle", subfolders:
    |
    | ../lasalle/pages/about.blade.php
    |                  home.blade.php
    |                  team.blade.php
    |           /layouts/
    |           /partials/
    |           /errors/
    |
*/



return [

	/*
	|--------------------------------------------------------------------------
	| Icon
	|--------------------------------------------------------------------------
	|
	| Path under the public folder, and filename, of the frontend's icon. Please
        | do not use a "/" as the first character. Example: public/icons/favicon.ico would 
        | 'icons/favicon.ico'. 
        |
        | Traditional favicon:
	|  * size: 16x16 or 32x32;
	|  * transparency is OK.
	| (http://mky.be/favicon/)
	|
	*/

	'icon' => 'favicon.ico',


    /*
	|--------------------------------------------------------------------------
	| Summary, or detailed, list display of posts
	|--------------------------------------------------------------------------
	|
	| I prefer blog posts listed in summary form. But, I prefer "about" posts displayed
    | in detail -- with full content -- in sequence in descending order.
    |
    | Yes, I prefer my static pages to be blog posts instead. And have multiple
    | posts instead of one. Static pages? What the heck are static pages?! Not in LaSalle Software!
    |
    | Sometimes I want to list each post in summary form, requiring a click to view the actual post.
    |
    | Sometimes I want to list each post in full on one big page, in descending order.
    |
    | This setting is where you specify how you prefer your category's display of posts for that
    | category: in summary form, or in detailed form.
	|
    | The category names must be the actual category titles.
    |
    | If a category is not listed here, then the posts will be displayed in summary form.
    |
	*/
    'database_pages_summary_or_detail' => [
        'Blog'  => 'summary',
        'About' => 'detail',
    ],


    /*
	|--------------------------------------------------------------------------
	| Do not query the database for posts.
	|--------------------------------------------------------------------------
	|
	| You can hand-craft pages in the blade files without using the database at all.
    |
  	| It is assumed that you will use the database, so the db is queried for posts.
	|
    | Well, if you are hand-crafting your page without using the database, then why
	| not save some loading time by skipping the database querying?
	|
    | For those pages that you are hand-crafting, specify them here to skip the db querying.
	|
	| Yes, even the home page is assumed to be using the database. So if you are not
    | availing your home page to the database, then list "Home" here.
	|
	| The category names must be the actual category titles.
    |
	*/
    'pages_not_using_database' => [
        'Home', 'Team',
    ],
];
