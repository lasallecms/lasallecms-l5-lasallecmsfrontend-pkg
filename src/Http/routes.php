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




/**
 * Blog feed route
 *
 * https://github.com/RoumenDamianoff/laravel-feed/wiki/basic-feed
 *
 *
 */
Route::get('/feed/blog', function() {

    $feed_number_of_posts = \Illuminate\Support\Facades\Config::get('lasallecmsfrontend.feed_number_of_posts');

    $todaysDate = \Lasallecms\Helpers\Dates\DatesHelper::todaysDateSetToLocalTime();

    // create new feed
    $feed = Feed::make();

    // cache the feed for 60 minutes (second parameter is optional)
    $feed->setCache(60, 'laravelFeedKey');

    // check if there is cached feed and build new only if is not
    if (!$feed->isCached())
    {
        // creating rss feed with our most recent $feed_number_of_posts posts
        $posts = DB::table('posts')
            ->where('publish_on', '<=', $todaysDate)
            ->where('enabled', '=', "1")
            ->orderby('updated_at', 'DESC')
            ->take($feed_number_of_posts)
            ->get();


        // set your feed's title, description, link, pubdate and language
        $feed->title =  \Illuminate\Support\Facades\Config::get('lasallecmsfrontend.feed_site_title');
        $feed->description =  \Illuminate\Support\Facades\Config::get('lasallecmsfrontend.site_description');
        $feed->logo =  \Illuminate\Support\Facades\Config::get('lasallecmsfrontend.social_media_default_image');
        $feed->link = URL::to('feed');
        $feed->setDateFormat('datetime'); // 'datetime', 'timestamp' or 'carbon'
        $feed->pubdate = $posts[0]->updated_at;
        $feed->lang = 'en';
        $feed->setShortening(true); // true or false
        $feed->setTextLimit(100); // maximum length of description text


        foreach ($posts as $post)
        {
            // set item's title, author, url, pubdate, description and content
            $feed->add($post->title,  \Illuminate\Support\Facades\Config::get('lasallecmsfrontend.site_author'), URL::to($post->slug), $post->updated_at, $post->excerpt, $post->content);
        }

    }

    // first param is the feed format
    // optional: second param is cache duration (value of 0 turns off caching)
    // optional: you can set custom cache key with 3rd param as string
    return $feed->render('atom');

    // to return your feed as a string set second param to -1
    // $xml = $feed->render('atom', -1);

});


/**
 * Sitemap
 *
 * https://github.com/RoumenDamianoff/laravel-sitemap/wiki/Generate-sitemap
 *
 *
 */
Route::get('mysitemap', function(){

    $todaysDate = \Lasallecms\Helpers\Dates\DatesHelper::todaysDateSetToLocalTime();

    // create new sitemap object
    $sitemap = App::make("sitemap");


    // add items to the sitemap (url, date, priority, freq)
    //$sitemap->add(URL::to(), '2012-08-25T20:10:00+02:00', '1.0', 'daily');
    //$sitemap->add(URL::to('page'), '2012-08-26T12:30:00+02:00', '0.9', 'monthly');



    // get all posts from db
    $posts = DB::table('posts')
        ->where('publish_on', '<=', $todaysDate)
        ->where('enabled', '=', "1")
        ->orderby('updated_at', 'DESC')
        ->get();
    //dd("mysitemap = ".$todaysDate);
    // add every post to the sitemap
    foreach ($posts as $post)
    {
        $sitemap->add($post->slug, $post->updated_at, '1.0', 'daily');
    }

    // generate your sitemap (format, filename)
    $sitemap->store('xml', 'sitemap');
    // this will generate file mysitemap.xml to your public folder

});





// single post by slug, or category listing (by title)
$router->get('{slug}', 'TriageController@triage');

// Home
$router->get('/', [
    'as'   => 'home',
    'uses' => 'TriageController@home',

]);