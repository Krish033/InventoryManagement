<?php


namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;

class PageHeaderService {

    /**
     * Main modules for the page
     * @var array
     */
    protected static $modules = ['master', 'transactions', 'users-and-permissions', 'reports'];

    /**
     * Contains methods
     * @var array
     */
    protected static $methods = ['create', 'edit', 'update', 'delete', 'restore', 'add'];

    /**
     * Main function -> returns the data
     * @param mixed $split
     * @return array
     */
    public static function page($split) {
        return self::url($split);
    }

    /**
     * Get the main address of the url
     * @param mixed $item
     * @return string
     */
    protected static function addressUrl(string $item): string {
        $currentUrl = url()->current();
        $splittedUrl = explode($item, $currentUrl)[0];
        // url '/' does not leave ending / in the current url 
        if ($splittedUrl[-1] !== '/') {
            $splittedUrl = $splittedUrl . '/';
        }
        // main route
        $mainRouteUrl = self::getItemUrl($item);
        $route = $splittedUrl . $item;

        if (!self::routeExists($mainRouteUrl)) {
            $route = "";
        }
        return $route;
    }

    /**
     * Get the url mathced by routes
     * @return mixed
     */
    protected static function getRouteUrl() {
        $mainUrl = url()->current();
        $appUrl = url('/') . '/';
        $currentRoute = self::cancelEmpty(explode($appUrl, $mainUrl));
        return Arr::first($currentRoute);
    }

    /**
     * Item url from route url
     * @param mixed $item
     * @return string
     */
    protected static function getItemUrl(string $item) {
        $routeUrl = self::getRouteUrl();
        $currentIteUrl = explode($item, $routeUrl)[0];
        return $currentIteUrl . $item;
    }

    /**
     * Check if the route exists
     * @param mixed $url
     * @return bool
     * ! finding in array doesnot cause performance issues
     */
    protected static function routeExists(string $url) {
        $routes = collect(Route::getRoutes()->get('GET'))->keys()->toArray();
        return in_array($url, $routes) ? true : false;
    }

    /**
     * Get Generated Url
     * @param mixed $split
     * @return array
     */
    protected static function url(string $split): array {
        // get the current 
        $url = url()->current();

        // Split it by the $split
        $splittedUrl = explode($split, $url);

        // if (!$splittedUrl[1] || is_null($splittedUrl[1])) {
        //     $splittedUrl[1] = "/dashboard";
        // }

        if (count($splittedUrl) < 2) {
            $splittedUrl[1] = "/dashboard";
        }

        // get indiviudual items
        $individualItems = self::cancelEmpty(explode('/', $splittedUrl[1]));
        $urls = []; // temp array

        // creating an object like structure
        foreach ($individualItems as $item) {
            $urls = [...$urls, ucfirst($item) => self::addressUrl($item)];
        }
        // main return
        return $urls;
    }

    /**
     * Cance empty values in an array
     * @param mixed $individualItems
     * @return array
     */
    protected static function cancelEmpty(array $individualItems): array {
        $main = [];
        // loopingt thorugh and fixing empty values
        foreach ($individualItems as $item) {
            if ($item != "") {
                array_push($main, $item);
            }
        } // array with no null values
        return $main;
    }


    public static function header() {
        $currentPage = self::getRouteUrl();
        // seprate the url 
        $header = explode('/', $currentPage);
        $main = [];

        // remove module nameand method name
        foreach ($header as $item) {
            if (!in_array($item, self::$methods) && !in_array($item, self::$modules) && !self::containsNumbers($item)) {
                array_push($main, $item);
            }
        } // if has multiple items
        if (count($main) > 1) {
            return ucfirst(implode(' ', $main));
        } // multiple items can be found, only the first element will be the header
        return ucfirst(Arr::first($main));
    }


    /**
     * Find numbers in string
     * @param mixed $string
     * @return bool|int
     */
    protected static function containsNumbers($string) {
        return preg_match('~[0-9]+~', $string);
    }

    /**
     * Find if ur in dashboard
     * @return bool
     */
    public static function reachedHome() {
        $pageUrl = url()->current(); // current url
        $appUrl = url('/'); // main app url
        // main app url - current url gives the exact page oyur in 
        $splitted = Arr::first(self::cancelEmpty(explode($appUrl, $pageUrl)));
        return $splitted === '/dashboard' || $splitted === '/' || $splitted === '' || is_null($splitted);
    }
}