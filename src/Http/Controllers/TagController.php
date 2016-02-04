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


// PLEASE REMEMBER THAT THE VIEW ROOT IS SPECIFIED IN THE APP's config/views.php "paths" setting (er, array element).
//

// LaSalle Software
use Lasallecms\Lasallecmsfrontend\Http\Controllers\FrontendBaseController;
use Lasallecms\Helpers\Dates\DatesHelper;
use Lasallecms\Helpers\HTML\HTMLHelper;
use Lasallecms\Helpers\Images\ImagesHelper;
use Lasallecms\Lasallecmsapi\Repositories\TagRepository;

// Laravel classes
use Illuminate\Filesystem\Filesystem;

// Laravel facades
use Illuminate\Support\Facades\Config;

class TagController extends FrontendBaseController
{
    /**
     * @var Lasallecms\Helpers\Images\ImagesHelper
     */
    protected $imagesHelper;

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /*
     * Instance of the BASE repository
     *
     * @var Lasallecms\Lasallecmsapi\Repositories\TagRepository
     */
    protected $repository;

    /*
     * Namespace and class name of the model
     *
     * @var string
     */
    protected $namespaceClassnameModel = "Lasallecms\Lasallecmsapi\Models\Tag";


    /**
     * @param ImagesHelper        $imagesHelper
     * @param CategoryRepository  $repository
     */
    public function __construct(
        ImagesHelper   $imagesHelper,
        TagRepository  $repository,
        Filesystem     $filesystem
    ) {

        // execute parent's construct method first in order to run the middleware
        parent::__construct();

        $this->imagesHelper = $imagesHelper;

        $this->repository   = $repository;
        $this->filesystem   = $filesystem;
    }


    /**
     * Display all publishable posts for the given category
     *
     * @param  $slug   The category's title
     * @return view
     */
    public function DisplayPostsByTag($title)
    {
        // Get the category
        // Please note that the categories table does *NOT* have a "slug" field!
        $tag = $this->repository->findTagIdByTitle($title);

        // if category does not exist
        if (count($tag) < 1 ) {
            return redirect('home');
        }


        // Process the tag's image

        // (i) resize the category image
        $this->imagesHelper->tagResizedImageFiles(Config::get('lasallecmsfrontend.default_tag_image'));

        // (ii) what is the name of the resized category image that the view will use?
        //      yeah, do this second as about to change $category->featured_image
        $tag->featured_image = $this->imagesHelper->tagImageResizedFilename(Config::get('lasallecmsfrontend.default_tag_image'));

        // Get the posts associated with this category
        $posts = $this->repository->findEnabledAllPostsThatHaveTagId($tag->id);

        if ($posts == 0)
        {
            // There are no enabled posts publish_on =< today
            //return view('errors/404');
            return redirect('home');
        }

        // Is the featured image in each post resized?
        $this->checkPostsImagesResized($posts);

        return view('tags/list_posts_associated_with_tag', [
            'tag'               => $tag,
            'posts'             => $posts,
            'pagetitle'         => $tag->title,
            'DatesHelper'       => DatesHelper::class,
            'HTMLHelper'        => HTMLHelper::class,
            'ImagesHelper'      => $this->imagesHelper,
        ]);
    }
}