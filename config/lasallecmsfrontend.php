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
    | Site author
    |--------------------------------------------------------------------------
    |
    | For the author meta tag.
    |
    */
    'site_author' => 'Bob Bloom',


    /*
    |--------------------------------------------------------------------------
    | Site description
    |--------------------------------------------------------------------------
    |
    | For the description meta tag.
    |
    | See og_description below.
    |
    */
    'site_description' => 'Flagship site of LaSalle Software, based on the superb Laravel Framework for PHP, plus blog. There is a separate Media site with podcasts.',


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


    /*
    |--------------------------------------------------------------------------
    | Uploaded images path
    |--------------------------------------------------------------------------
    |
    | What is the path, under your public folder, where your uploaded images reside?
    |
    | This folder for images that are uploaded.
    |
    | Eg: "images" = public/images
    |
    */
    'images_folder_uploaded' => 'images',

    /*
    |--------------------------------------------------------------------------
    | Resizsed images path
    |--------------------------------------------------------------------------
    |
    | What is the path, under your public folder, where your resized images reside?
    |
    | This folder for images that LaSalle resizes.
    |
    | Eg: "images_resized" = public/images_resized
    |
    */
    'images_folder_resized' => 'images_resized',


    /*
    |--------------------------------------------------------------------------
    | Image sizes
    |--------------------------------------------------------------------------
    |
    | What image sizes are required by the front-end?
    |
    | Image resizing handled by the terrific Intervention package.
    |
    | When the image size you are requesting is bigger than the source image, only the source image dimensions are used.
    |
    | "@2x" images are created automatically.
    |
    | The array is set up as [ width => height ].
    |
    | 600x600 for social media tags.
    |
    */
    'image_sizes' => [
        1900 => 1200,
        600  => 600,
        300  => 300,
        150  => 150,
    ],

    /*
    |--------------------------------------------------------------------------
    | Default category image
    |--------------------------------------------------------------------------
    |
    | When no category image is defined, what image do you want to use?
    |
    */
    'default_category_image' => 'category_image.jpg',


    /*
    |--------------------------------------------------------------------------
    | Image size for category image
    |--------------------------------------------------------------------------
    |
    | When posts for a category are displayed, the category's featured image is displayed at the top of the listing.
    |
    | What is the size of this image?
    |
    | Remember: the image should be twice as big, in order to use "@2x" for the retina plugin.
    |
    */
    'category_featured_image_size' => [ 1900 => 350 ],


    /*
    |--------------------------------------------------------------------------
    | Path to the view (blade) files
    |--------------------------------------------------------------------------
    |
    | What is path to the blade files?
    |
    | This path MUST be specified in the config/view.php "paths" setting.
    |
    */
    'pathToTheBladeFiles' => 'resources/views/lasalle',


    /*
    |--------------------------------------------------------------------------
    | Name of the generic blade file for pages
    |--------------------------------------------------------------------------
    |
    | What is the name of the generic file in your blade files (views) to display pages?
    |
    | You can have a blade file called "about.blade.php" for your About page. Or, instead of hand crafting
    | "about.blade.php", have a blade file that handles the pages instead.
    |
    | Yo, do *NOT* append your file name with ".blade.php"!
    |
    | BTW, just to be a nag, remember that the categories in your "categories" table in the database
    | are pages.
    |
    */
    'nameOfGenericBladeFile' => 'generic_pages',


    /*
    |--------------------------------------------------------------------------
    | Allowed IP addresses for front end only
    |--------------------------------------------------------------------------
    |
    | What IP addresses are allowed to look at the front end? This has nothing to
    | do with logging in! Stress that this pertains to the front end only -- there
    | is separate handling for this on the admin side.
    |
    | If this array is not blank, then everyone who is *not* from these IP addresses
    | will be redirected to the "not allowed" page
    |
    | Completely optional. To ignore, just leave the array blank.
    |
    */
    //'frontend_allowed_ip_addresses' => [ '127.0.0.1', '128.0.0.0', ],
    'frontend_allowed_ip_addresses' => [],

    /*
    |--------------------------------------------------------------------------
    | Excluded IP addresses for front end only
    |--------------------------------------------------------------------------
    |
    | What IP addresses are excluded from looking at the front end? This has nothing to
    | do with logging in! Stress that this pertains to the front end only.
    |
    | When the array is *not* blank, it means everyone can look at the front end except
    | these IP addresses.
    |
    | Completely optional. To ignore, just leave the array blank.
    |
    */
    //'frontend_excluded_ip_addresses' => [ '127.0.0.1', '128.0.0.0', ],
    'frontend_excluded_ip_addresses' => [],

    /*
    |--------------------------------------------------------------------------
    | Front end is down / display flash page
    |--------------------------------------------------------------------------
    |
    | Do not allow anyone to look at the front end. Instead, redirect everyone
    | to a splash page.
    |
    | Exception: anyone logged into the admin is allowed to view the front end.
    |
    | true or false.
    |
    */
    'frontend_display_flash_page' => false,



    /*
    |===============================================================================================
    |                                ***  Default Open Graph Markup Tags  ***
    |===============================================================================================
    |
    | Tag descriptions are straight from https://developers.facebook.com/docs/sharing/webmasters
    |
    | These are tags used when individual page tags are unavailable, kind-of, sort-of. EG, the home page(?!)
    |
    | I am still feeling my way through these OG tags
    |
    | https://moz.com/blog/meta-data-templates-123
    |
    */

    /*
     * og:url
     *
     * The canonical URL for your page. This should be the undecorated URL, without session variables,
     * user identifying parameters, or counters. Likes and Shares for this URL will aggregate at this URL.
     * For example, mobile domain URLs should point to the desktop version of the URL as the canonical URL
     * to aggregate Likes and Shares across different versions of the page.
     */
    'og_url' => '',

    /*
     * og:title
     *
     * The title of your article without any branding such as your site name.
     */
    'og_title' => 'Home page',

    /*
     * og:description
     *
     * A brief description of the content, usually between 2 and 4 sentences. This will
     * displayed below the title of the post on Facebook.
     */
    'og_description' => 'Flagship site of LaSalle Software, based on the superb Laravel Framework for PHP, plus blog. There is a separate Media site with podcasts.',

    /*
     * og:site_name
     *
     * The name of your website (such as IMDb, not imdb.com).
     */
    'og_site_name' => "South LaSalle",

    /*
     * og:type
     *
     * The type of media of your content. This tag impacts how your content shows up in News Feed.
     * If you don't specify a type,the default is website. Each URL should be a single object, so multiple
     * og:type values are not possible. Find the full list of object types in our Object Types Reference
     *
     * https://developers.facebook.com/docs/reference/opengraph#object-type
     */
    'og_type' => 'article',


    /*
    |--------------------------------------------------------------------------
    | Twitter card tags
    |--------------------------------------------------------------------------
    |
    | https://dev.twitter.com/cards/
    |
    */
    'twitter_card'      => 'summary_large_image',
    'twitter_site'      => '@bobbloom',
    'twitter_creator'   => '@bobbloom',

    /*
    |--------------------------------------------------------------------------
    | Default image
    |--------------------------------------------------------------------------
    |
    | When a post has no featured image
    |
    */
    'social_media_default_image'  => 'http://southlasalle.com/images_tsltc/southlasalle-logo.png',

];
