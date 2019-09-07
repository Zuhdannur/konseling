<?php

namespace App\Http\Middleware;

use Closure;

class LinkHeaderPagination
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
        if (!$request->isMethod('get')) {
            return $next($request);
        }

        $response = $next($request);

        $bodyContent = json_decode($response->getContent(), true);

        if ($request->count == true) {
            $response->header('X-Total-Count', $bodyContent['meta']['pagination']['total']);
        }

        if ($bodyContent && array_key_exists('meta', $bodyContent)) {
            $params = $this->getParams($request);

            $links = $this->createPaginationLinks($bodyContent, $request->url(), $params);

            $response->header('Link', $links);

            unset($bodyContent['meta']);

            $response->setContent('asd');
        }

        return $response;
    }

    /**
     * Format query string args.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $url
     * @param string $params
     * @return string
     */
    public function createPaginationLinks($bodyContent, $url, $params)
    {
        $pagination = $bodyContent['meta']['pagination'];

        $pages = $this->getPageNumbers($pagination);

        $prev = null;
        // if (array_key_exists('prev', $pagination['prev_page_url'])) {
        $prev = "<{$url}{$params}page={$pages->prev}&page_size={$pagination['per_page']}>; rel=\"prev\"";
        // }

        $next = null;
        // if (array_key_exists('next', $pagination['next_page_url'])) {
        $next = "<{$url}{$params}page={$pages->next}&page_size={$pagination['per_page']}>; rel=\"next\"";
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

    /**
     * Format query string args.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function getParams($request)
    {
        $query = '';

        if ($request->query()) {
            $params = $this->cleanParams($request->query());

            foreach ($params as $key => $value) {
                $query .= $key . '=' . $value . '&';
            }
        }

        return $query ? '?' . $query : '?';
    }

    /**
     * Clean unecessari params form query string
     *
     * @param array $params
     * @return array
     */
    public function cleanParams($params)
    {
        $clean = ['p', 'page', 'per_page'];

        foreach ($clean as $value) {
            if (array_key_exists($value, $params)) {
                unset($params[$value]);
            }
        }

        return $params;
    }

    /**
     * Get the page numbers to the pagination
     *
     * @param array $pagination
     * @return object
     */
    public function getPageNumbers($pagination)
    {
        $total = $pagination['total'];
        $pagination['current_page'] >= $total
            ? $current = $total
            : $current = $pagination['current_page'];

        return (object)[
            'current' => $current,
            'next'    => $current >= $total ? null : $current + 1,
            'prev'    => $current <= 1 ? null : $current -1
        ];
    }
}
