<?php

namespace App\Http\Controllers\User\Etfs;

use App\Http\Controllers\Controller;
use App\Queries\Etfs\FilteredEtfsQuery;
use App\Services\EtfFilters\EtfFilterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Utilities\Auth;

class EtfsListController extends Controller
{
    public function listEtfs(Request $request, EtfFilterService $filterService)
    {
        try {

            $filters = $filterService->resolve($request->all());

            $etfs = (new FilteredEtfsQuery())->getData(
                $filters,
                Auth::id()
            );

        } catch (\Exception $e) {

            Log::error('Failed to fetch filtered ETFs', [
                'user_id' => Auth::id(),
                'filters' => $request->all(),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Oops, something went wrong. Please try again later.',
            ], 500);

        }

        return response()->json([
            'success' => true,
            'data' => $etfs,
        ], 200);
    }
}