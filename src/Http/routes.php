<?php

/**
 *
 * Front-end package for the LaSalle Content Management System, based on the Laravel 5 Framework
 * Copyright (C) 2015 - 2016  The South LaSalle Trading Corporation
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
 * @copyright  (c) 2015 - 2016, The South LaSalle Trading Corporation
 * @license    http://www.gnu.org/licenses/gpl-3.0.html
 * @author     The South LaSalle Trading Corporation
 * @email      info@southlasalle.com
 *
 */


/* *********************************************************************************************** */
/* *********************************************************************************************** */
/*
 *  Originally, I placed front-end routes in \Lasallecms\Lasallecmsfrontend\Http\routes.php.
 *  Seems like a good idea because the standard front-end routes emanate from this package. But, not
 *  such a great idea when it's desirable to customize those routes.
 *
 *  To allow the "home" and "{slug}" to be customized -- or over-ridden -- I am extracting the two main
 *  routes out of my front-end package, and placing them here in my Flagship's route.php.
 *
 *  Update: Actually, I am putting 'em back, and wrapping each route here with a conditional based on
 *  config settings.
 *
 */
/* *********************************************************************************************** */
/* *********************************************************************************************** */



/* ***********************************************************************************************
 *  Front-end "site" routes
 * *********************************************************************************************** */



/* -----------------------------------------------------------------------------------------------
   HOME
   ----------------------------------------------------------------------------------------------- */
if (config('lasallecmsfrontend.frontend_route_home')) {
    $router->get('/', [
        'as'   => 'home',
        'uses' => '\Lasallecms\Lasallecmsfrontend\Http\Controllers\PostController@home',
    ]);

    $router->get('home', '\Lasallecms\Lasallecmsfrontend\Http\Controllers\PostController@home');
}



/* -----------------------------------------------------------------------------------------------
   DISPLAY ALL POSTS BY CATEGORY
   ----------------------------------------------------------------------------------------------- */
if (config('lasallecmsfrontend.frontend_route_display_posts_by_category')) {
    $router->get('/category/{title}', '\Lasallecms\Lasallecmsfrontend\Http\Controllers\CategoryController@DisplayPostsByCategory');
}



/* -----------------------------------------------------------------------------------------------
   DISPLAY ALL POSTS BY TAG
   ----------------------------------------------------------------------------------------------- */
if (config('lasallecmsfrontend.frontend_route_display_posts_by_tag')) {
    $router->get('tag/{slug}', '\Lasallecms\Lasallecmsfrontend\Http\Controllers\TagController@DisplayPostsByTag');
}



/* -----------------------------------------------------------------------------------------------
   In the event "404" is the route!/var/www/html/lasallecms-l5-packages/packages/lasallecms/lasallecmsadmin
   ----------------------------------------------------------------------------------------------- */
if (config('lasallecmsfrontend.frontend_route_404')) {
    $router->get('404', [
        'as'   => '404',
        'uses' => 'FrontendController@fourohfour',

    ]);
}

/* -----------------------------------------------------------------------------------------------
   In the event "503" is the route!
   ----------------------------------------------------------------------------------------------- */
if (config('lasallecmsfrontend.frontend_route_503')) {
    $router->get('503', [
        'as'   => '503',
        'uses' => 'FrontendController@fiveohthree',

    ]);
}


/* -----------------------------------------------------------------------------------------------
   FEED (https://github.com/RoumenDamianoff/laravel-feed/wiki/basic-feed)
   ----------------------------------------------------------------------------------------------- */
if (config('lasallecmsfrontend.frontend_route_blog_feed')) {
    $router->get('/feed/blog', function () {

        $feed_number_of_posts = \Illuminate\Support\Facades\Config::get('lasallecmsfrontend.feed_number_of_posts');

        $todaysDate = \Lasallecms\Helpers\Dates\DatesHelper::todaysDateSetToLocalTime();

        // create new feed
        $feed = Feed::make();

        // cache the feed for 60 minutes (second parameter is optional)
        $feed->setCache(60, 'laravelFeedKey');

        // check if there is cached feed and build new only if is not
        if (!$feed->isCached()) {
            // creating rss feed with our most recent $feed_number_of_posts posts
            $posts = DB::table('posts')
                ->where('publish_on', '<=', $todaysDate)
                ->where('enabled', '=', "1")
                ->orderby('updated_at', 'DESC')
                ->take($feed_number_of_posts)
                ->get();


            // set your feed's title, description, link, pubdate and language
            $feed->title = \Illuminate\Support\Facades\Config::get('lasallecmsfrontend.feed_site_title');
            $feed->description = \Illuminate\Support\Facades\Config::get('lasallecmsfrontend.site_description');
            $feed->logo = \Illuminate\Support\Facades\Config::get('lasallecmsfrontend.social_media_default_image');
            $feed->link = URL::to('feed');
            $feed->setDateFormat('datetime'); // 'datetime', 'timestamp' or 'carbon'
            $feed->pubdate = $posts[0]->updated_at;
            $feed->lang = 'en';
            $feed->setShortening(true); // true or false
            $feed->setTextLimit(100); // maximum length of description text


            foreach ($posts as $post) {
                // set item's title, author, url, pubdate, description and content
                $feed->add($post->title, \Illuminate\Support\Facades\Config::get('lasallecmsfrontend.site_author'),
                    URL::to($post->slug), $post->updated_at, $post->excerpt, $post->content);
            }

        }

        // first param is the feed format
        // optional: second param is cache duration (value of 0 turns off caching)
        // optional: you can set custom cache key with 3rd param as string
        return $feed->render('atom');

        // to return your feed as a string set second param to -1
        // $xml = $feed->render('atom', -1);

    });
}


/* -----------------------------------------------------------------------------------------------
   SITEMAP (https://github.com/RoumenDamianoff/laravel-sitemap/wiki/Generate-sitemap)
   ----------------------------------------------------------------------------------------------- */
if (config('lasallecmsfrontend.frontend_route_site_map')) {
    $router->get('sitemap', function () {

        $todaysDate = \Lasallecms\Helpers\Dates\DatesHelper::todaysDateSetToLocalTime();

        // create new sitemap object
        $sitemap = App::make("sitemap");

        // get all posts from db
        $posts = DB::table('posts')
            ->where('publish_on', '<=', $todaysDate)
            ->where('enabled', '=', "1")
            ->orderby('updated_at', 'DESC')
            ->get();
        //dd("mysitemap = ".$todaysDate);
        // add every post to the sitemap
        foreach ($posts as $post) {
            $sitemap->add($post->slug, $post->updated_at, '1.0', 'daily');
        }

        // generate your sitemap (format, filename)
        $sitemap->store('xml', 'sitemap');
        // this will generate file mysitemap.xml to your public folder

    });
}




/* -----------------------------------------------------------------------------------------------
   SINGLE POST BY SLUG
   -----------------------------------------------------------------------------------------------
 Evaluate the slug, except when that slug happens to be the exact word "admin". So, do this by:

 $router->get('{slug}', '\Lasallecms\Lasallecmsfrontend\Http\Controllers\TriageController@triage')
         ->where('slug', '!=", 'admin')
 ;

 Wrong wrong wrong! This not eloquent! This is a Route Parameter. Only two vars, and the second var
 is a regular expression.SINGLE POST BY SLUG

 OK, fine, so find out what the regular expression is for "NOT admin". After searching for this regex,
 I gave up, especially when one sure-fire solution resulted in more errors (which sums up my life's
 journey with regex).

 Ultimately, after trying out many things, I found the Request helper method. Bingo!

 http://laravel.com/docs/5.1/helpers#method-request
 * ----------------------------------------------------------------------------------------------- */
if (!Request::is('admin'))
{
    if (config('lasallecmsfrontend.frontend_route_single_post')) {
        $router->get('{slug}', '\Lasallecms\Lasallecmsfrontend\Http\Controllers\PostController@DisplaySinglePost');
    }
}
