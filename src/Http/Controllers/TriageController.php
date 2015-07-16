<?php

namespace Lasallecms\Lasallecmsfrontend\Http\Controllers;

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


// PLEASE REMEMBER THAT THE VIEW ROOT IS SPECIFIED IN THE APP's config/views.php "paths" setting (er, array element).


/*
    |--------------------------------------------------------------------------
    | CATEGORY, POST, SOMETHING ELSE? IT ALL LOOKS THE SAME!
    |--------------------------------------------------------------------------
    |
    | Listing of all blog posts looks like:  https://domain.com/blog
    |
    | Listing of one single blog post:       https://domain.com/mary-had-a-little-lamb
    |
    | *Maybe* a product will end up like this: https://domain.com/widget
    |
    | They all look the same. So how to discern? Let's figure it out first, then
    | process the route.
    |
    | It is tempting to just assess and process in this one single controller, because
    | there are just two models/repositories, and this controller is relatively small.
    | However, my BaseRepository is designed to be injected in one class (methinks!), which
    | I can probably subvert but why bother? So, this controller will assess the route, then
    | give over route processing to another class in this package that injects the right
    | repository. Then, once the processing is done, come back to this controller to
    | launch the view. The processing is very lean, basically fetch the data from the repository,
    | but this intermediate class is necessary to inject the repository.
    |
*/


// PLEASE REMEMBER THAT THE VIEW ROOT IS SPECIFIED IN THE APP's config/views.php "paths" setting (er, array element).


// LaSalle Software
use Lasallecms\Lasallecmsfrontend\Http\Controllers\FrontendBaseController;
use Lasallecms\Lasallecmsfrontend\Processing\CategoryProcessing;
use Lasallecms\Lasallecmsfrontend\Processing\PostProcessing;
use Lasallecms\Helpers\Dates\DatesHelper;
use Lasallecms\Helpers\HTML\HTMLHelper;
use Lasallecms\Helpers\Images\ImagesHelper;

// Laravel facades
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

// Laravel classes
use Illuminate\Filesystem\Filesystem;

class TriageController extends FrontendBaseController
{
    /**
     * @var Lasallecms\Lasallecmsfrontend\Processing\CategoryProcessing
     */
    protected $categoryProcessing;

    /**
     * @var Lasallecms\Lasallecmsfrontend\Processing\PostProcessing
     */
    protected $postProcessing;

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * @var Lasallecms\Helpers\Images\ImagesHelper
     */
    protected $imagesHelper;

    /**
     * @var  The category ID
     */
    protected $categoryId;



    /**
     * @param CategoryProcessing $categoryProcessing
     * @param PostProcessing $postProcessing
     * @param Filesystem $files
     */
    public function __construct(
        CategoryProcessing $categoryProcessing,
        PostProcessing $postProcessing,
        Filesystem $files,
        ImagesHelper $imagesHelper
    ) {

        // TODO: FRONTEND MIDDLEWARE!
        //$this->middleware('auth');

        // execute parent's construct method first in order to run the middleware
        parent::__construct();

        $this->categoryProcessing = $categoryProcessing;
        $this->postProcessing     = $postProcessing;
        $this->files              = $files;
        $this->imagesHelper       = $imagesHelper;
    }



    ///////////////////////////////////////////////////////////////////
    /////             SPECIAL METHOD FOR THE HOME PAGE            /////
    ///////////////////////////////////////////////////////////////////
    public function home()
    {
        $slug = 'home';

        // Skip any database querying and go straight to the page?
        if ($this->skipDatabaseQuery($slug)) {
            return view('pages/home', [
                'DatesHelper'       => DatesHelper::class,
                'HTMLHelper'        => HTMLHelper::class,
                'ImagesHelper'      => $this->imagesHelper,
            ]);
        }

        // Get the posts associated with the "home" category
        $posts = $this->getPosts($slug);

        // Is the featured image in each post resized?
        $this->checkPostsImagesResized($posts);

        // view
        return view('pages/home', [
            'posts'             => $posts,
            'DatesHelper'       => DatesHelper::class,
            'HTMLHelper'        => HTMLHelper::class,
            'ImagesHelper'      => $this->imagesHelper,
            'isSummaryOrDetail' => $this->isSummaryOrDetail($slug),
        ]);
    }


    ///////////////////////////////////////////////////////////////////
    /////             SPECIAL METHOD FOR ERROR PAGES              /////
    ///////////////////////////////////////////////////////////////////
    public function fourohfour()
    {
        return view('special_pages/404');
    }

    public function fiveohthree()
    {
        return view('special_pages/503');
    }



    ///////////////////////////////////////////////////////////////////
    /////                      MAIN METHOD                        /////
    ///////////////////////////////////////////////////////////////////

    /**
     * Determine if the route pertains to a category, post, or something else.
     *
     * The route must be in the form https://domain.com/{slug}
     *
     * @param   text   $slug     Either a slug, or title, depending on what the route represents
     * @return  view
     */
    public function triage($slug)
    {
        $slug = strtolower($slug);


        // Skip any database querying and go straight to the page?
        // There is no defaulting to a page if there are no database records. So make sure this
        // config setting is set when there are no posts.
        if ($this->skipDatabaseQuery($slug))
        {
            return view('pages/' . $slug);
        }

        // Does this route pertain to a category?
        // If so, then we must list all the posts (filtered by enabled and publish_on) for that category
        if ($this->isCategory($slug))
        {
            // Get the category
            $category = $this->categoryProcessing->getCategory($this->categoryId);

            // Process the category's image

            // (i) resize the category image
            $this->imagesHelper->createCategoryResizedImageFiles($category->featured_image);

            // (ii) what is the name of the resized category image that the view will use?
            //      yeah, do this second as about to change $category->featured_image
            $category->featured_image = $this->imagesHelper->getCategoryFeaturedImage($category->featured_image);


            // Get the posts associated with this category
            $posts = $this->getPosts($this->categoryId);

            if (count($posts) == 0)
            {
                // There are no enabled posts publish_on =< today
                return view('special_pages/404');
            }

            // Is the featured image in each post resized?
            $this->checkPostsImagesResized($posts);


            // if there is a separate blade file for this page, then view this separate blade file
           if ($this->isBladeFile($slug)) {
                return view('pages/' . $slug, [
                    'category'          => $category,
                    'posts'             => $posts,
                    'pagetitle'         => $category->title,
                    'DatesHelper'       => DatesHelper::class,
                    'HTMLHelper'        => HTMLHelper::class,
                    'ImagesHelper'      => $this->imagesHelper,
                    'isSummaryOrDetail' => $this->isSummaryOrDetail($slug),
                ]);
            }

            // if there is a generic blade file for pages, then view this generic blade file
            if ($this->isGenericPageFile($slug)) {
                return view('pages/' . Config::get('lasallecmsfrontend.nameOfGenericBladeFile'), [
                    'category'          => $category,
                    'posts'             => $posts,
                    'pagetitle'         => $category->title,
                    'DatesHelper'       => DatesHelper::class,
                    'HTMLHelper'        => HTMLHelper::class,
                    'ImagesHelper'      => $this->imagesHelper,
                    'isSummaryOrDetail' => $this->isSummaryOrDetail($slug),
                ]);
            }

            // Uh-oh, still here? Then there is no blade file to view this page.
            return view('errors/404');
        }

        // It's a single post
        $post = $this->postProcessing->getPostBySlug($slug);

        if (count($post) == 0) {
            return view('special_pages/404');
        }

        // Figure out if the post is using a specified featured image; or, if no such image,
        // then use the category's default image as this post's featured image.
        $post->featured_image = $this->imagesHelper->categoryImageDefaultOrSpecified($post->featured_image);

        // Resize the post's featured image -- if not yet resized.
        $this->imagesHelper->createPostResizedImageFiles($post->featured_image);


        // Set up the social media tags

        // first, set up the image
        if ($post->featured_image == "") {
             $post->featured_image = Config::get('lasallecmsfrontend.social_media_default_image');
        }
        $post->urlImage = $this->imagesHelper->urlOfImage($post->featured_image,600,600);


        // second, create the social media tag arrays
        $openGraph = HTMLHelper::createOpenGraphTagsForPost($post);
        $twitter   = HTMLHelper::createTwitterTagsForPost($post);
        $google    = HTMLHelper::createGoogleTagsForPost($post);


        return view('pages/single_post', [
            'post'              => $post,
            'DatesHelper'       => DatesHelper::class,
            'HTMLHelper'        => HTMLHelper::class,
            'ImagesHelper'      => $this->imagesHelper,
            'openGraph'         => $openGraph,
            'twitter'           => $twitter,
            'google'            => $google,
        ]);
    }



    ///////////////////////////////////////////////////////////////////
    /////       METHODS THAT SUPPORT THE MAIN TRIAGE METHOD       /////
    ///////////////////////////////////////////////////////////////////

    /**
     * Skip querying the database for this page?
     *
     * @param   string $slug
     * @return  bool
     */
    public function skipDatabaseQuery($slug)
    {
        $pagesSkippingDatabaseQuery = Config::get('lasallecmsfrontend.pages_not_using_database');

        foreach ($pagesSkippingDatabaseQuery as $page)
        {
            if ($page == ucwords($slug))
            {
                return true;
            }
        }

        return false;
    }


    /**
     * Is this slug actually a category title?
     *
     * @param   string $slug
     * @return  bool
     */
    public function isCategory($slug)
    {
        $category = $this->categoryProcessing->getCategoryId($slug);

        if ($category == "") {
            return false;
        }

        // Yes, this is a category!

        // Let's save the category ID for further processing
        $category = $category->toArray();
        $this->categoryId = $category['id'];

        // return true
        return true;
    }


    /**
     * Does a blade file exist for this page?
     *
     * @param   string $slug
     * @return  bool
     */
    public function isBladeFile($slug)
    {
        $fullPath = base_path() . '/' . Config::get('lasallecmsfrontend.pathToTheBladeFiles') . '/pages/' . $slug . '.blade.php';

        if ($this->files->isFile($fullPath)) {
            return true;
        }

        return false;
    }


    /**
     * Does a generic blade file for pages exist?
     *
     * @param   string      $slug
     * @return  bool
     */
    public function isGenericPageFile($slug)
    {
        $fullPath = base_path() . '/' . Config::get('lasallecmsfrontend.pathToTheBladeFiles') . '/pages/' . Config::get('lasallecmsfrontend.nameOfGenericBladeFile') . '.blade.php';

        if ($this->files->isFile($fullPath)) {
            return true;
        }

        return false;
    }


    /**
     * Get posts by category slug
     *
     * @param   int          $categoryId      The category's ID
     * @return  collection
     */
    public function getPosts($categoryId)
    {
        return $this->categoryProcessing->getPostsByCategoryId($categoryId);
    }


    /**
     * Display the posts for a category in "summary" or "detail" form?
     *
     * @param   string      $slug
     * @return  text
     */
    public function isSummaryOrDetail($slug)
    {
        $pagesSummaryOrDetail = Config::get('lasallecmsfrontend.database_pages_summary_or_detail');

        foreach ($pagesSummaryOrDetail as $category => $type)
        {
            if ($category == ucwords($slug))
            {
                return $type;
            }
        }

        return "summary";
    }



    ///////////////////////////////////////////////////////////////////
    /////         METHODS THAT SUPPORT IMAGE HANDLING             /////
    ///////////////////////////////////////////////////////////////////

    /**
     *
     * Iterate through posts to check if the featured images are resized.
     *
     * @param  collection   $posts
     * @return void
     */
    public function checkPostsImagesResized($posts)
    {
        foreach ($posts as $post)
        {
            if ($post->featured_image != "")
            {
                $this->imagesHelper->createPostResizedImageFiles($post->featured_image);
            }
        }
    }


    /**
     *
     * Resize category's featured image
     *
     * @param  string   $categoryFeaturedImage
     * @return void
     */
    public function resizeCategoryFeaturedImage($categoryFeaturedImage)
    {
        $this->imagesHelper->createResizedImageFiles($categoryFeaturedImage, true);
    }
}