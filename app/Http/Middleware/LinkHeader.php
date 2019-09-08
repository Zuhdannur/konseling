<?php

namespace App\Http\Middleware;

use Closure;

class ExampleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // let other middleware handle the request first
        $response = $next($request);

        $links = 

        // get paging links here
        // <url>; rel="next", <url>; rel="prev", <url>; rel="first", <url>; rel="last"
        $response->header("Link: " . $links);

        return $response;
    }

    public function createPaginationLinks($bodyContent, $url, $params)
    {
        $pagination = $bodyContent['meta']['pagination'];

        // $pages = $this->getPageNumbers($pagination);

        // if (array_key_exists('prev', $pagination['prev_page_url'])) {
        $prev = "<{$url}{$params}page={$pagination['prev']}&page_size={$pagination['per_page']}>; rel=\"prev\"";
        // }


        // if (array_key_exists('next', $pagination['next_page_url'])) {
        $next = "<{$url}{$params}page={$pagination['next']}&page_size={$pagination['per_page']}>; rel=\"next\"";
        // }

        $first = "<{$url}{$params}page=1&page_size={$pagination['per_page']}>; rel=\"first\"";

        $last = "<{$url}{$params}page={$pagination['last_page']}&page_size={$pagination['per_page']}>; rel=\"last\"";

        if ($prev && $next) {
            $links = "{$prev}, {$next}, {$first}, {$last}";
        } elseif ($prev && !$next) {
            $links = "{$prev}, {$first}, {$last}";
        } elseif (!$prev && $next) {
            $links = "{$next}, {$first}, {$last}";
        } else {
            $links = null;
        }

        return $links;
    }

}
