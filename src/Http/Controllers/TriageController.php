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
use Lasallecms\Lasallecmsfrontend\Http\Controllers\Controller;
use Lasallecms\Lasallecmsfrontend\Processing\CategoryProcessing;
use Lasallecms\Lasallecmsfrontend\Processing\PostProcessing;
use Lasallecms\Helpers\Dates\DatesHelper;
use Lasallecms\Helpers\HTML\HTMLHelper;

// Laravel facades
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

// Laravel classes
use Illuminate\Filesystem\Filesystem;

class TriageController extends Controller
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
     * Path to the view files
     *
     * @var string
     */
    protected $path;

    /**
     * Name of the generic blade file for pages.
     *
     * If you do not have a separate blade file for (some) pages, then you'll need a generic blade file for
     * (some) pages.
     *
     * @var string
     */
    protected $nameOfGenericBladeFile = 'generic_pages.blade.php';


    /**
     * @param CategoryProcessing $categoryProcessing
     * @param PostProcessing $postProcessing
     * @param Filesystem $files
     */
    public function __construct(
        CategoryProcessing $categoryProcessing,
        PostProcessing $postProcessing,
        Filesystem $files
    ) {
        $this->middleware('auth');

        // TODO: FRONTEND MIDDLEWARE!

        $this->categoryProcessing = $categoryProcessing;
        $this->postProcessing = $postProcessing;
        $this->files = $files;

        $this->path = realpath(base_path('resources/views/lasalle'));
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
                'DatesHelper' => DatesHelper::class,
                'HTMLHelper'  => HTMLHelper::class,
            ]);
        }

        // view
        return view('pages/home', [
            'posts'             => $this->getPosts($slug),
            'DatesHelper'       => DatesHelper::class,
            'HTMLHelper'        => HTMLHelper::class,
            'isSummaryOrDetail' => $this->isSummaryOrDetail($slug),
        ]);
    }



    ///////////////////////////////////////////////////////////////////
    /////                      MAIN METHOD                        /////
    ///////////////////////////////////////////////////////////////////

    /**
     * Determine if the route pertains to a category, post, or something else.
     *
     * The route must be in the form https://domain.com/{slug}
     *
     * @param   text $slug Either a slug, or title, depending on what the route represents
     * @return  view
     */
    public function triage($slug)
    {
        // Skip any database querying and go straight to the page?
        if ($this->skipDatabaseQuery($slug)) {
            return view('pages/' . $slug);
        }

        // Does this route pertain to a category?
        if ($this->isCategory($slug)) {

            // if there is a separate blade file for this page, then view this separate blade file
            if ($this->isBladeFile($slug)) {
                return view('pages/' . $slug, [
                    'posts'             => $this->getPosts($slug),
                    'DatesHelper'       => DatesHelper::class,
                    'HTMLHelper'        => HTMLHelper::class,
                    'isSummaryOrDetail' => $this->isSummaryOrDetail($slug),
                ]);
            }

            // if there is a generic blade file for pages, then view this generic blade file
            if ($this->isGenericPageFile($slug)) {
                return view('pages/' . $this->nameOfGenericBladeFile, [
                    'posts'             => $this->getPosts($slug),
                    'DatesHelper'       => DatesHelper::class,
                    'HTMLHelper'        => HTMLHelper::class,
                    'isSummaryOrDetail' => $this->isSummaryOrDetail($slug),
                ]);
            }

            // Uh-oh, still here? Then there is no blade file to view this page.
            return view('errors/404');
        }


        // It's a single post
        $post = $this->postProcessing->getPostBySlug($slug);

        if (count($post) == 0) {
            return view('errors/404');
        }

        return view('posts/single_post', [
            'post'              => $post,
            'DatesHelper'       => DatesHelper::class,
            'HTMLHelper'        => HTMLHelper::class,
            'isSummaryOrDetail' => $this->isSummaryOrDetail($slug),
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
        $fullPath = $this->path . '/pages/' . $slug . '.blade.php';
        if ($this->files->isFile($fullPath))
        {
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
        $fullPath = $this->path . '/pages/' . $this->nameOfGenericBladeFile;

        if ($this->files->isFile($fullPath))
        {
            return true;
        }

        return false;
    }


    /**
     * Get posts by category slug
     *
     * @param   text          $slug  The category's slug
     * @return  collection
     */
    public function getPosts($slug)
    {
        $categoryId = $this->categoryProcessing->getCategoryId($slug);

        return $this->categoryProcessing->getPostsByCategoryId($categoryId->id);
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
}