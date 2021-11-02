<?php

namespace App\Repositories;

use App\Models\Voyage;
use App\Models\Vessel;

class VoyageRepository {

    /**
     * @var Voyage
     */
    protected $voyage;

    /**
     * VoyageRepository constructor.
     *
     * @param Voyage $voyage
     */
    public function __construct(Voyage $voyage)
    {
        $this->voyage = $voyage;
    }

    /**
     * Get all Voyages.
     *
     * @return Voyage $voyage
     */
    public function getAll()
    {
        return $this->voyage
            ->get();
    }

    /**
     * Get Voyage by id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->voyage
            ->where('id', $id)
            ->get();
    }

    /**
     * Save Voyage
     *
     * @param $data
     * @return Voyage
     */
    public function create($data)
    {

        $vessel = Vessel::where('id', $data['vessel_id'])->get()->first();

        $voyage = new $this->voyage;

        $voyage->vessel_id = $data['vessel_id'];
        $voyage->start = $data['start'];
        $voyage->end = $data['end'];
        $voyage->revenues = $data['revenues'];
        $voyage->expenses = $data['expenses'];
        $voyage->status = 'pending';
        $voyage->code = $vessel->name.'-'.$data['start'];

        $voyage->save();

        return $voyage->fresh();
    }

    /**
     * Update Voyage
     *
     * @param $data
     * @return Voyage
     */
    public function update($data, $id)
    {
        
        $voyage = $this->voyage->find($id);

        /// can't edit voyage that already submitted.
        if ($voyage->status == 'submitted') {
            return 'already submitted';
        }

        /// can't have two 'ongoing' voyage for one vessel.
        if ($data['status'] == 'ongoing') {
            $exist = $this->voyage->where(
                                [['id', '<>', $id], ['vessel_id', $voyage->vessel_id], ['status', 'ongoing']]
                            )->exists();
            if ($exist) {
                return "vessel can't have two ongoing voyages";
            }
        }

        $voyage->start = $data['start'];
        $voyage->end = $data['end'];
        $voyage->revenues = $data['revenues'];
        $voyage->expenses = $data['expenses'];
        $voyage->status = $data['status'];


        //// update profit when voyage submitted
        if ($data['status'] == 'submitted') {
            $voyage->profit = $data['revenues'] - $data['expenses'];
        }

        $voyage->update();

        return $voyage;
    }

    /**
     * Update Voyage
     *
     * @param $data
     * @return Voyage
     */
    public function delete($id)
    {
        
        $voyage = $this->voyage->find($id);
        $voyage->delete();

        return $voyage;
    }
}