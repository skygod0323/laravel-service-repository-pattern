<?php

namespace App\Services;

use App\Repositories\VoyageRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class VoyageService {

    protected $voyageRepository;

    public function __construct(VoyageRepository $voyageRepository) {
        $this->voyageRepository = $voyageRepository;
    }

    public function getAll() {
        return $this->voyageRepository->getAll();
    }

    public function createVoyage($data) {
        
        $validator = Validator::make($data, [
            'vessel_id' => 'required',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
            'revenues' => 'required',
            'expenses' => 'required'
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        $result = $this->voyageRepository->create($data);

        return $result;
    }

    public function updateVoyage($data, $id) {

        $validator = Validator::make($data, [
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
            'revenues' => 'required',
            'expenses' => 'required',
            'status' => 'required|in:pending,ongoing,submitted'
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        DB::beginTransaction();

        try {
            $post = $this->voyageRepository->update($data, $id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to update post data');
        }

        DB::commit();

        return $post;
    }
}