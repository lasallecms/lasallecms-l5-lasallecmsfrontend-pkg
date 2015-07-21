<?php

namespace Lasallecms\Lasallecmsfrontend\Http\Middleware;

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

// LaSalle Software
use  Lasallecms\Usermanagement\Http\Middleware\Admin\CustomAdminAuthChecks;

// Laravel facades
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

// Laravel classes
use Illuminate\Http\Request;

// PHP
use Closure;


class CustomFrontendChecks
{
    /**
     * @var Lasallecms\Usermanagement\Http\Middleware\Admin\CustomAdminAuthChecks
     */
    protected $customAdminAuthChecks;


    /**
     * Inject!
     */
    public function __construct(CustomAdminAuthChecks $customAdminAuthChecks)
    {
        $this->customAdminAuthChecks = $customAdminAuthChecks;
    }


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  Lasallecms\Usermanagement\Http\Middleware\Admin\CustomAdminAuthChecks
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Allowed IP Addresses
        if (!$this->frontend_allowed_ip_addresses($request))
        {
            return view('special_pages.not_allowed_to_look_at_front_end');
        }

        // Excluded IP Addresses
        if ($this->frontend_excluded_ip_addresses($request))
        {
            return view('special_pages.not_allowed_to_look_at_front_end');
        }


        if (!Auth::check())
        {
            // Front end is down. Display flash page. Anyone logged into the admin is exempt
            if ($this->frontend_display_flash_page())
            {
                return view('special_pages.splash_page');
            }
        }

        return $next($request);
    }


    /**
     * Check if the current IP address is allowed to look at the front end.
     *
     * @return bool
     */
    public function frontend_allowed_ip_addresses($request)
    {
        $frontend_allowed_ip_addresses = config('lasallecmsfrontend.frontend_allowed_ip_addresses');

        // "true" = allowed
        if (empty($frontend_allowed_ip_addresses)) return true;

        $requestIPAddress = $this->customAdminAuthChecks->getRequestIPAddress($request);

        return $this->customAdminAuthChecks->ipAddressCheck($frontend_allowed_ip_addresses, $requestIPAddress);
    }

    /**
     * Check if the current IP address is excluded from looking at the front end.
     *
     * @return bool
     */
    public function frontend_excluded_ip_addresses($request)
    {
        $frontend_excluded_ip_addresses = config('lasallecmsfrontend.frontend_excluded_ip_addresses');

        // "true" = exclude
        if (empty($frontend_excluded_ip_addresses)) return false;

        $requestIPAddress = $this->customAdminAuthChecks->getRequestIPAddress($request);

        return $this->customAdminAuthChecks->ipAddressCheck($frontend_excluded_ip_addresses, $requestIPAddress);
    }


    /**
     * Check if the front end is down. Current logged-in users in the admin are exempt.
     *
     * @return bool
     */
    public function frontend_display_flash_page()
    {
        $frontend_display_flash_page = config('lasallecmsfrontend.frontend_display_flash_page');

        if ($frontend_display_flash_page) return true;

        return false;
    }
}