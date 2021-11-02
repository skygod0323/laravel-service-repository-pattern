<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\VoyageService;

class VoyageController extends Controller
{
    /**
     * @var voyageService
     */
    protected $voyageService;


    /**
     * PostController Constructor
     *
     * @param VoyageService $voyageService
     *
     */
    public function __construct(VoyageService $voyageService)
    {
        $this->voyageService = $voyageService;
    }

    public function index() {

        $result = ['status' => 200];

        try {
            $result['data'] = $this->voyageService->getAll();
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    public function store(Request $request)
    {
        $data = $request->only([
            'vessel_id',
            'start',
            'end',
            'revenues',
            'expenses',
        ]);

        $result = ['status' => 200];

        try {
            $result['data'] = $this->voyageService->createVoyage($data);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }


    /// update voyage
    public function update(Request $request, $id)
    {
        $data = $request->only([
            'start',
            'end',
            'revenues',
            'expenses',
            'status'
        ]);

        $result = ['status' => 200];

        try {
            $result['data'] = $this->voyageService->updateVoyage($data, $id);

        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);

    }

}