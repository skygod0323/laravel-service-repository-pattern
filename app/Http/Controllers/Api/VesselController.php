<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vessel;
use App\Services\VesselService;

class VesselController extends Controller
{
    protected $vesselService;

    public function __construct(VesselService $vesselService)
    {
        $this->vesselService = $vesselService;
    }

    public function index() {
        
    }

    public function addVesselOpex(Request $request, $vessel_id) {
        $data = $request->only([
            'date',
            'expenses',
        ]);

        $result = ['status' => 200];

        try {
            $result['data'] = $this->vesselService->addVesselOpex($data, $vessel_id);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    public function financial_report(Request $request, $vessel_id) {

        $result = ['status' => 200];

        try {
            $result['data'] = $this->vesselService->financial_report($vessel_id);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }
}