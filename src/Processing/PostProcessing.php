<?php

namespace Lasallecms\Lasallecmsfrontend\Processing;

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

// LaSalle Software
use Lasallecms\Lasallecmsapi\Repositories\BaseRepository;

// Laravel facades
use Illuminate\Support\Facades\Config;

//class PostProcessing implements FrontendRouteProcessing
class PostProcessing
{
    /*
     * Instance of the BASE repository
     *
     * @var Lasallecms\Lasallecmsapi\Repositories\BaseRepository
     */
    protected $repository;

    /*
     * Namespace and class name of the model
     *
     * @var string
     */
    protected $namespaceClassnameModel = "Lasallecms\Lasallecmsapi\Models\Post";

    /**
     * Create a new controller instance.
     *
     * @param Lasallecms\Lasallecmsapi\Repositories\BaseRepository
     */
    public function __construct(BaseRepository $repository)
    {
        $this->repository = $repository;

        $this->repository->injectModelIntoRepository($this->namespaceClassnameModel);
    }

    /*
     * Get post by slug.
     *
     * Post is enabled and publish_on <= today
     *
     * @param  text   $slug     The post's slug
     * @return array
     */
    public function getPostBySlug($slug)
    {
        return $this->repository->findEnabledPostBySlug($slug);
    }
}