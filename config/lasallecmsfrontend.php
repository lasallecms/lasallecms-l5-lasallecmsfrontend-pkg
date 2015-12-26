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
    | ../lasalle/layouts/
    | ../lasalle/partials/
    | ../lasalle/errors/
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
    | Site Name
    |--------------------------------------------------------------------------
    |
    | Appears in the admin header
    |
    */
    'site_name' => env('APP_SITE_NAME'),



    /*
    |--------------------------------------------------------------------------
    | Site author
    |--------------------------------------------------------------------------
    |
    | For the author meta tag.
    |
    | For the feed.
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
    | For the feed.
    |
    | See og_description below.
    |
    */
    'site_description' => 'Flagship site of LaSalle Software, based on the superb Laravel Framework for PHP, plus blog. There is a separate Media site with podcasts.',


    /*
    |--------------------------------------------------------------------------
    | http or https
    |--------------------------------------------------------------------------
    |
    | Is this site secure. That is, "http://" or "https://". Generally, you want your
    | site to be secure.
    |
    | Supposed to use Laravel's "env" for this, but I want to try out a config setting.
    |
    | My Helper package uses this setting, which is referenced by the flagship's KERNAL.php.
    |
    | For both front and back end.
    |
    | true or false
    |
    */
    'secureURL' => env('APP_SITE_SECURE'),



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
    | Number of posts to display on the home page
    |--------------------------------------------------------------------------
    |
    | The number of most-recent publishable posts to display on the home page.
    |
    | If the above "pages_not_using_database" includes "Home", then this setting is moot.
    |
    */
    'number_of_posts_to_display_on_home_page' => 5,


    /*
    |--------------------------------------------------------------------------
    | Acceptable image file types (extensions) for uploading
    |--------------------------------------------------------------------------
    |
    | What image types are ok for uploading.
    |
    | For, but not exclusively for, uploading featured images
    |
    | Three character extensions only.
    |
    | Lowercase only!
    |
    */
    'acceptable_image_extensions_for_uploading' => [
        'bmp', 'gif', 'jpg', 'png',
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
    'category_featured_image_size' => [ 1900 => 400 ],

    /*
    |--------------------------------------------------------------------------
    | Default tag image
    |--------------------------------------------------------------------------
    |
    | There is no image associated with each tag.
    | All tags will be associated with this image.
    |
    */
    'default_tag_image' => 'tag_image.jpg',


    /*
    |--------------------------------------------------------------------------
    | Image size for tag image
    |--------------------------------------------------------------------------
    |
    | When posts for a tag are displayed, the tag's default image is displayed at the top of the listing.
    |
    | What is the size of this image?
    |
    | Remember: the image should be twice as big, in order to use "@2x" for the retina plugin.
    |
    */
    'default_tag_image_image_size' => [ 1900 => 400 ],


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
    | Frontend Template Folder
    |--------------------------------------------------------------------------
    |
    | What is the name of your frontend template? 
    | That is, where is your frontend folder.
    |
    | Your lasallecmsfrontend views are located at:
    |
    | resources/views/vendor/lasallecmsfrontend/frontend/frontend_template_name/
    |
    */
    'frontend_template_name' => 'default',


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
    'frontend_display_flash_page' => env('APP_FRONTEND_DISPLAY_FLASH_PAGE'),


    /*
    |--------------------------------------------------------------------------
    | Do not display posts belonging to these categories on the home page.
    |--------------------------------------------------------------------------
    |
    | Do you have categories whose posts should not display on the home page?
    |
    | List the Category names (titles) in an array.
    |
    */
    //'frontend_suppress_categories_on_home_page' => ['Landing-Pages'],
    'frontend_suppress_categories_on_home_page' => [],



    /*
    |===============================================================================================
    |                           ***  START: FRONTEND ROUTES  ***
    |===============================================================================================
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Front-end route: home
    |--------------------------------------------------------------------------
    |
    | true  = use the "HOME" in this package's routes.php
    |
    | false = do *not* use the "home" route that resides in the front-end package's routes.php
    |       ==> if set to false, then make sure you have this route in your app's routes.php

    |
    */
    'frontend_route_home' => true,

    /*
    |--------------------------------------------------------------------------
    | Front-end route: single post by slug
    |--------------------------------------------------------------------------
    |
    | true  =use the "SINGLE POST BY SLUG" in this package's routes.php
    |
    | false = do *not* use the "single post" route that resides in the front-end package's routes.php
    |       ==> if set to false, then make sure you have this route in your app's routes.php
    |
    */
    'frontend_route_single_post' => true,

    /*
    |--------------------------------------------------------------------------
    | Front-end route: display posts by category
    |--------------------------------------------------------------------------
    |
    | true  = use the "DISPLAY ALL POSTS BY CATEGORY" in this package's routes.php
    |
    | false = do *not* use the "single post" route that resides in the front-end package's routes.php
    |       ==> if set to false, then make sure you have this route in your app's routes.php
    |
    */
    'frontend_route_display_posts_by_category' => true,

    /*
    |--------------------------------------------------------------------------
    | Front-end route: display posts by tag
    |--------------------------------------------------------------------------
    |
    | true  = use the "DISPLAY ALL POSTS BY TAG" in this package's routes.php
    |
    | false = do *not* use the "single post" route that resides in the front-end package's routes.php
    |       ==> if set to false, then make sure you have this route in your app's routes.php
    |
    */
    'frontend_route_display_posts_by_tag' => true,

    /*
    |--------------------------------------------------------------------------
    | Front-end route: 404
    |--------------------------------------------------------------------------
    |
    | true  = use the 404 route in this package's routes.php
    |
    | false = do *not* use the 404 route that resides in the front-end package's routes.php
    |       ==> if set to false, then it is optional you have this route in your app's routes.php
    |
    | true or false.
    |
    */
    'frontend_route_404' => true,

    /*
    |--------------------------------------------------------------------------
    | Front-end route: 503
    |--------------------------------------------------------------------------
    |
    | true  = use the 503 route in this package's routes.php
    |
    | false = do *not* use the 503 route that resides in the front-end package's routes.php
    |       ==> if set to false, then it is optional that you have this route in your app's routes.php
    |
    | true or false.
    |
    */
    'frontend_route_503' => true,

    /*
    |--------------------------------------------------------------------------
    | Front-end route: blog feed
    |--------------------------------------------------------------------------
    |
    | true  = use the blog feed route in this package's routes.php
    |
    | false = do *not* use the blog feed route that resides in the front-end package's routes.php
    |       ==> if set to false, then it is optional that you have this route in your app's routes.php
    |
    | true or false.
    |
    */
    'frontend_route_blog_feed' => true,

    /*
    |--------------------------------------------------------------------------
    | Front-end route: sitemap
    |--------------------------------------------------------------------------
    |
    | true  = use the sitemap route in this package's routes.php
    |
    | false = do *not* use the sitemap route that resides in the front-end package's routes.php
    |       ==> if set to false, then it is optional that you have this route in your app's routes.php
    |
    | true or false.
    |
    */
    'frontend_route_site_map' => true,




    /*
    |===============================================================================================
    |                           ***  START: SOCIAL MEDIA META TAGS  ***
    |===============================================================================================
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Facebook open graphs tags
    |--------------------------------------------------------------------------
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
    |--------------------------------------------------------------------------
    | og:description
    |--------------------------------------------------------------------------
    |
    | A brief description of the content, usually between 2 and 4 sentences. This will
    | displayed below the title of the post on Facebook.
    |
    */
    'og_description' => 'Flagship site of LaSalle Software, based on the superb Laravel Framework for PHP, plus blog. There is a separate Media site with podcasts.',

    /*
    |--------------------------------------------------------------------------
    | og:site_name
    |--------------------------------------------------------------------------
    |
    | The name of your website (such as IMDb, not imdb.com).
    |
    */
    'og_site_name' => env('APP_SITE_NAME'),

    /*
    |--------------------------------------------------------------------------
    | og:type
    |--------------------------------------------------------------------------
    |
    | The type of media of your content. This tag impacts how your content shows up in News Feed.
    | If you don't specify a type,the default is website. Each URL should be a single object, so multiple
    | og:type values are not possible. Find the full list of object types in our Object Types Reference
    |
    | https://developers.facebook.com/docs/reference/opengraph#object-type
    |
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
    | When a post has no featured image.
    |
    | Also for the feed.
    |
    */
    'social_media_default_image'  => 'http://southlasalle.com/images_tsltc/southlasalle-logo.png',

    /*
    |===============================================================================================
    |                           ***  END: SOCIAL MEDIA META TAGS  ***
    |===============================================================================================
    |
    */



    /*
    |--------------------------------------------------------------------------
    | Feed
    |--------------------------------------------------------------------------
    |
    | Based on https://github.com/RoumenDamianoff/laravel-feed
    |
    */
    'feed_number_of_posts' => 25,
    'feed_site_title'      => 'South LaSalle Dot Com',


];
