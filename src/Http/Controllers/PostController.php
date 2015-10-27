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
 * @link       http://LaSalleCMS.com
 * @copyright  (c) 2015, The South LaSalle Trading Corporation
 * @license    http://www.gnu.org/licenses/gpl-3.0.html
 * @author     The South LaSalle Trading Corporation
 * @email      info@southlasalle.com
 *
 */


// PLEASE REMEMBER THAT THE VIEW ROOT IS SPECIFIED IN THE APP's config/views.php "paths" setting (er, array element).


// LaSalle Software
use Lasallecms\Lasallecmsfrontend\Http\Controllers\FrontendBaseController;
use Lasallecms\Helpers\Dates\DatesHelper;
use Lasallecms\Helpers\HTML\HTMLHelper;
use Lasallecms\Helpers\Images\ImagesHelper;
use Lasallecms\Lasallecmsapi\Repositories\PostRepository;

// Laravel classes
use Illuminate\Filesystem\Filesystem;

// Larave facades
use Illuminate\Support\Facades\Config;

class PostController extends FrontendBaseController
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
     * @var Lasallecms\Lasallecmsapi\Repositories\PostRepository
     */
    protected $repository;

    /*
     * Namespace and class name of the model
     *
     * @var string
     */
    protected $namespaceClassnameModel = "Lasallecms\Lasallecmsapi\Models\Post";

    /**
     * @param ImagesHelper   $imagesHelper
     * @param PostRepository $repository
     */
    public function __construct(
        ImagesHelper    $imagesHelper,
        PostRepository  $repository,
        Filesystem      $filesystem
    ) {

        // execute parent's construct method first in order to run the middleware
        parent::__construct();

        $this->imagesHelper = $imagesHelper;

        $this->repository   = $repository;
        $this->filesystem   = $filesystem;
    }


    /**
     * Home page
     *
     * @return view
     */
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

        // Get the posts to display on the home page
        $posts = $this->repository->getSomePublishablePosts(Config::get('lasallecmsfrontend.number_of_posts_to_display_on_home_page'));

        // Is the featured image in each post resized?
        $this->checkPostsImagesResized($posts);

        // view
        return view('pages/home', [
            'posts'             => $posts,
            'DatesHelper'       => DatesHelper::class,
            'HTMLHelper'        => HTMLHelper::class,
            'ImagesHelper'      => $this->imagesHelper,
        ]);
    }



    /**
     * Display the individual post
     *
     * @param $slug  Post's slug
     * @return view
     */
    public function DisplaySinglePost($slug)
    {
        // If this is a page, then display the page
        if ($this->isBladeFile($slug)) {
            return view('pages/' . $slug, [
                'DatesHelper'       => DatesHelper::class,
                'HTMLHelper'        => HTMLHelper::class,
                'ImagesHelper'      => $this->imagesHelper,
            ]);
        }


        // This slug refers to a single post
        $post = $this->repository->findEnabledPostBySlug($slug);

        if (count($post) == 0) {

            // post not found, so display the 404 page
            //return view('errors/404');
            return $this->home();
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
}