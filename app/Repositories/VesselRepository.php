<?php

namespace App\Repositories;

use App\Models\Voyage;
use App\Models\Vessel;
use App\Models\VesselOpex;
use Illuminate\Support\Facades\DB;

class VesselRepository {

    /**
     * @var Vessel
     */
    protected $vessel;

    /**
     * VoyageRepository constructor.
     *
     * @param Vessel $vessel
     */
    public function __construct(Vessel $vessel)
    {
        $this->vessel = $vessel;
    }

    /// add vessel opex
    public function addVesselOpex($data, $vessel_id) {

        /// if opex exist on same date return
        if (VesselOpex::where([['vessel_id', $vessel_id], ['date', $data['date']]])->exists()) {
            return "A vessel cannot have two different opex amounts for the same date";
        }

        $vesselOpx = new VesselOpex;
        $vesselOpx->vessel_id = $vessel_id;
        $vesselOpx->date = $data['date'];
        $vesselOpx->expenses = $data['expenses'];

        $vesselOpx->save();

        return $vesselOpx->fresh();
    }

    public function financial_report($vessel_id) {


        /// need to check this sql query because I have to understand about the fields clearly.
        $sql = "SELECT vessels.id, t1.expenses as voyage_expenses, t1.revenues as voyage_revenues,
                    t1.revenues - t1.expenses as voyage_profit,
                    t1.start, t1.end
                    from vessels LEFT JOIN 
                (
                    SELECT vessel_id, sum(expenses) as expenses, sum(revenues) as revenues,
                    min(start) as start, max(voyages.end) as end
                    FROM voyages GROUP BY vessel_id
                ) t1 ON vessels.id = t1.vessel_id WHERE vessels.id = " . $vessel_id;
        
        $result = DB::select($sql);

        return $result;

    }
}