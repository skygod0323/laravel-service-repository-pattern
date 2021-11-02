<?php

namespace App\Services;

use App\Repositories\VesselRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class VesselService {

    protected $vesselRepository;

    public function __construct(VesselRepository $vesselRepository) {
        $this->vesselRepository = $vesselRepository;
    }


    //// add opex
    public function addVesselOpex($data, $vessel_id) {
        $validator = Validator::make($data, [
            'date' => 'required|date',
            'expenses' => 'required',
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        $result = $this->vesselRepository->addVesselOpex($data, $vessel_id);

        return $result;
    }


    /// financial report
    public function financial_report($vessel_id) {
        return $this->vesselRepository->financial_report($vessel_id);
    }
}