<?php

namespace Lasallecms\Lasallecmsfrontend\Http\Controllers;

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




// Laravel facades
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

// Laravel classes
use Illuminate\Foundation\Bus\DispatchesJobs;
// Base controller from https://github.com/laravel/laravel/blob/master/app/Http/Controllers/Controller.php
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

class FrontendBaseController extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    /**
     * Execute middleware
     */
    public function __construct()
    {
        // User must be logged to access everything in this package
        $this->middleware(\Lasallecms\Lasallecmsfrontend\Http\Middleware\CustomFrontendChecks::class);
    }


    ///////////////////////////////////////////////////////////////////
    /////             SPECIAL METHOD FOR ERROR PAGES              /////
    ///////////////////////////////////////////////////////////////////
    public function fourohfour()
    {
        return view('errors/404');
    }

    public function fiveohthree()
    {
        return view('errors/503');
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
     * Does a blade file exist for this page?
     *
     * @param   string $slug
     * @return  bool
     */
    public function isBladeFile($slug)
    {
        $fullPath = base_path() . '/' . Config::get('lasallecmsfrontend.pathToTheBladeFiles') . '/pages/' . $slug . '.blade.php';

        if ($this->filesystem->isFile($fullPath)) {
            return true;
        }

        return false;
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