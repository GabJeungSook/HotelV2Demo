<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\CheckinDetail;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GuestSearchController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = trim($request->query('q', ''));
            $perPage = (int) $request->query('per_page', config('api.guest_search.per_page', 15));
            $maxPerPage = config('api.guest_search.max_per_page', 100);

            if ($perPage < 1) {
                $perPage = 15;
            }
            if ($perPage > $maxPerPage) {
                $perPage = $maxPerPage;
            }

            $branchId = (int) $request->query('branch_id');
            $includeCheckedOut = filter_var($request->query('include_checked_out', false), FILTER_VALIDATE_BOOLEAN);

            $guests = Guest::query()
                ->when($query !== '', function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%");
                })
                ->when($branchId > 0, function ($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                })
                ->when(!$includeCheckedOut, function ($q) {
                    $q->whereHas('checkInDetail', function ($q) {
                        $q->where('is_check_out', false);
                    });
                })
                ->with([
                    'room:id,number,branch_id',
                    'checkInDetail.frontdesk:id,name',
                ])
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            $guests->getCollection()->transform(function ($guest) {
                return [
                    'id' => $guest->id,
                    'name' => $guest->name,
                    'contact' => $guest->contact,
                    'room_id' => $guest->room_id,
                    'room_number' => $guest->room?->number,
                    'created_at' => $guest->created_at?->toIso8601String(),
                    'is_check_out' => $guest->checkInDetail?->is_check_out ?? true,
                    'frontdesk' => $guest->checkInDetail?->frontdesk ? [
                        'id' => $guest->checkInDetail->frontdesk->id,
                        'name' => $guest->checkInDetail->frontdesk->name,
                    ] : null,
                ];
            });

            return ApiResponse::paginated($guests, 'Guests retrieved', null, 200);
        } catch (\Exception $e) {
            Log::error('Guest Search API Error: ' . $e->getMessage(), [
                'trace' => $e->getTrace(),
            ]);
            return ApiResponse::error('An error occurred while retrieving guests.');
        }
    }
}
