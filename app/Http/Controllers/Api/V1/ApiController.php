<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class ApiController extends Controller
{
    /**
     * Resolve a safe page size from the request (validated input is preferred).
     */
    protected function perPage(Request $request, int $default = 15, int $max = 100): int
    {
        $perPage = (int) $request->input('per_page', $default);

        return max(1, min($perPage, $max));
    }
}
