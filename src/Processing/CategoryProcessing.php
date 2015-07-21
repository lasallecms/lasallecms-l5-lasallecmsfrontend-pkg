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
 * @link       http://LaSalleCMS.com
 * @copyright  (c) 2015, The South LaSalle Trading Corporation
 * @license    http://www.gnu.org/licenses/gpl-3.0.html
 * @author     The South LaSalle Trading Corporation
 * @email      info@southlasalle.com
 *
 */

// PLEASE REMEMBER THAT THE VIEW ROOT IS SPECIFIED IN THE APP's config/views.php "paths" setting (er, array element).

// LaSalle Software
use Lasallecms\Lasallecmsapi\Repositories\CategoryRepository;

// Laravel facades
use Illuminate\Support\Facades\Config;

class CategoryProcessing
{
    /**
     * Instance of the BASE repository
     *
     * @var Lasallecms\Lasallecmsapi\Repositories\CategoryRepository
     */
    protected $repository;

    /**
     * Namespace and class name of the model
     *
     * @var string
     */
    protected $namespaceClassnameModel = "Lasallecms\Lasallecmsapi\Models\Category";

    /**
     * Create a new controller instance.
     *
     * @param Lasallecms\Lasallecmsapi\Repositories\BaseRepository
     */
    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * Get the category ID from the category TITLE.
     *
     * Enabled categories only!
     *
     * @param   string   $categoryTitle   The name of the category
     * @return  int
     */
    public function getCategoryId($categoryTitle)
    {
        return $this->repository->findCategoryIdByTitle($categoryTitle);
    }

    /**
     * Get the category record from the category ID
     *
     * Enabled categories only!
     *
     * @param   int   $categoryId   The ID of the category
     * @return  int
     */
    public function getCategory($categoryId)
    {
        return $this->repository->findCategoryById($categoryId);
    }

    /**
     * Get the posts associated with a specific category ID.
     *
     * Enabled posts only.
     * Posts publish_on today or before today.
     *
     * @param   int         $catId    The category ID
     * @return  collection
     */
    public function getPostsByCategoryId($catId)
    {
        return $this->repository->findEnabledAllPostsThatHaveCategoryId($catId);
    }
}