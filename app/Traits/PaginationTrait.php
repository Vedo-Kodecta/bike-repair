<?php

namespace App\Traits;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

trait PaginationTrait
{
    public function customPagination($items, $perPage = 5, $page = null)
    {
        if (request('per_page')) {
            $perPage = (int)request('per_page');
        }
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $total = count($items);
        $currentpage = $page;
        $offset = ($currentpage * $perPage) - $perPage;
        $itemstoshow = $items->slice($offset, $perPage);
        return new LengthAwarePaginator($itemstoshow, $total, $perPage);
    }
}
