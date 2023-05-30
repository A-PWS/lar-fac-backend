<?php

namespace App\Http\Controllers;
use App\Models\NShift;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class NShiftController extends Controller
{

    public function getData()
    {
        $shiftModel = new NShift();
        $data = $shiftModel -> getShift();
        return response()->json($data);
    }

    public function addData(Request $req)
    {
        $json = array();
        $shiftModel = new NShift();
        $result = $shiftModel->addShift($req->all());

       if($result)
       {
        $json['code'] = 1;
        $json['message'] = 'Details saved succeessfully';
       }

       else
       {
        $json['code'] = 2;
        $json['message'] = 'Error while saving data';
       }

       return response() -> json($json);
    }

    function deleteData(Request $req)
    {
        $shiftModel = new NShift();

        $id = $req->id;

      $del_result = $shiftModel -> deleteData($id);

      if($del_result)
      {
       $json['code'] = 1;
       $json['message'] = 'Record deleted succeessfully';
      }

      else
      {
       $json['code'] = 2;
       $json['message'] = 'Error while deleting data';
      }

      return response()->json($json);

    }

   //update
   public function getOneData(Request $req)
    {
        $shiftModel = new NShift();
        $id = $req->id;
        $data = $shiftModel -> getOneShift($id);
        return response()->json($data);
    }

    public function updateData(Request $req)
    {
        $json = array();
        $id = $req->id;
        $shiftModel = new NShift();
        $result = $shiftModel->updateShift($id, $req->all());
       dd($result);

       if($result)
       {
        $json['code'] = 1;
        $json['message'] = 'Details updated succeessfully';
       }

       else
       {
        $json['code'] = 2;
        $json['message'] = 'Error while updated data';
       }

       return response() -> json($json);
    }


    //get one reject
    public function getOneReject(Request $req)
    {
        $shiftModel = new NShift();
        $id = $req->id;

        $data = $shiftModel -> getOneReject($id);
        return response()->json($data);
    }


    //shift report
    public function getShiftReport()
    {

       return DB::table('n_shifts')
    ->join(DB::raw('(SELECT shiftId, SUM(quantity) as total_quantity FROM hours GROUP BY shiftId) hours'), 'n_shifts.id', '=', 'hours.shiftId')
    ->join('reject_reasons', 'n_shifts.id', '=', 'reject_reasons.shiftId')
    ->select('n_shifts.id', 'n_shifts.shiftType', DB::raw('DATE_FORMAT(n_shifts.date, "%m/%d/%Y") as date'), 'hours.total_quantity', 'n_shifts.carderCount', 'n_shifts.manPower', DB::raw('SUM(reject_reasons.count) as total_count'))
    ->whereRaw('MONTH(n_shifts.date) = ?', [date('m')])
    ->whereRaw('YEAR(n_shifts.date) = ?', [date('Y')])
    ->groupBy('n_shifts.id', 'n_shifts.shiftType', 'n_shifts.date', 'n_shifts.carderCount', 'n_shifts.manPower', 'hours.total_quantity')
    ->get();
    }
    
    //shift report reject
    public function getShiftReportReject()
    {
    


        return DB::table('reject_reasons')
        ->select(DB::raw('reject_reasons.hour, reject_reasons.shiftId, DATE(reject_reasons.date) as date, SUM(reject_reasons.count) as total_count, reject_reasons.reason'))
        ->whereRaw('MONTH(reject_reasons.date) = ?', [date('m')])
        ->whereRaw('YEAR(reject_reasons.date) = ?', [date('Y')])
        ->groupBy('reject_reasons.hour', 'reject_reasons.shiftId', DB::raw('DATE(reject_reasons.date)'), 'reject_reasons.reason')
        ->orderBy('reject_reasons.id', 'desc')
        ->get();

    
        
    }

    //Hourly Production table
    public function getHourlyProductionTable()
    {
    

    return DB::table('hours')
    ->join('n_shifts', 'n_shifts.id', '=', 'hours.shiftId')
    ->join('reject_reasons', 'reject_reasons.shiftId', '=', 'hours.shiftId')
    ->select('n_shifts.id', 'hours.shiftId', 'hours.hour', 'hours.quantity', 'reject_reasons.count')
    ->distinct()
    ->get();
        
    }

    //----------------------------***Newly aded***----------------------
    //get data to dashboard

    public function dashboardTotalProductionUpToDate(){


        $nShiftsLastEnteredDate = DB::table('n_shifts')
        ->orderBy('date', 'desc')
        ->value('date');

    return DB::table('hours')
        ->join('n_shifts', 'hours.shiftId', '=', 'n_shifts.id')
        ->select(DB::raw('SUM(hours.quantity) as total_quantity'))
        ->whereDate('n_shifts.date', '=', $nShiftsLastEnteredDate)
        ->get();

    }


public function dashboardTotalRejectionUpToDate(){
    $nShiftsLastEnteredDate = DB::table('n_shifts')
    ->orderBy('date', 'desc')
    ->value('date');

return DB::table('n_shifts')
    ->join('reject_reasons', 'n_shifts.id', '=', 'reject_reasons.shiftId')
    ->select(DB::raw('SUM(reject_reasons.count) as total_count'))
    ->whereDate('n_shifts.date', '=', $nShiftsLastEnteredDate)
    ->get();

}


//Daily Rejection Summary

public function getDailyReject()
{
         $lastEnteredDate = DB::table('n_shifts')
                    ->orderByDesc('date')
                    ->pluck('date')
                    ->first();

    $results = DB::table('reject_reasons as r')
    ->leftJoin(DB::raw("(SELECT reason, sum(count) as Daycount
           FROM reject_reasons
           INNER JOIN n_shifts ON reject_reasons.shiftId = n_shifts.id 
           WHERE n_shifts.date = '$lastEnteredDate' AND n_shifts.shiftType = 'Day' 
           GROUP BY reason) as y"), 'r.reason', '=', 'y.reason')
    ->leftJoin(DB::raw("(SELECT reason, sum(count) as Nightcount
           FROM reject_reasons
           INNER JOIN n_shifts ON reject_reasons.shiftId = n_shifts.id 
           WHERE n_shifts.date = '$lastEnteredDate' AND n_shifts.shiftType = 'Night'
           GROUP BY reason) as z"), 'r.reason', '=', 'z.reason')
    ->select(['r.reason', DB::raw("IFNULL(y.Daycount, 0) as Daycount"), DB::raw("IFNULL(z.Nightcount, 0) as Nightcount"), DB::raw("'$lastEnteredDate' as day")])
    ->distinct()
    ->get();

// Iterate through the results and remove the "\r\n" characters from the reason field
foreach ($results as $result) {
    $result->reason = str_replace("\r\n", "", $result->reason);
}

return $results;



}


public function dashboardAVG(){
    $nShiftsLastEnteredDate = DB::table('n_shifts')
        ->orderBy('date', 'desc')
        ->value('date');

        $rejectTotal = DB::table('reject_reasons')
        ->join('n_shifts', 'reject_reasons.shiftId', '=', 'n_shifts.id')
        ->select(DB::raw('SUM(count) as total_count'))
        ->whereDate('n_shifts.date', '=', $nShiftsLastEnteredDate)
        ->value('total_count');

    $prodTotal = DB::table('hours')
        ->join('n_shifts', 'hours.shiftId', '=', 'n_shifts.id')
        ->select(DB::raw('SUM(quantity) as total_quantity'))
        ->whereDate('n_shifts.date', '=', $nShiftsLastEnteredDate)
        ->value('total_quantity');

    $avg = ($rejectTotal + $prodTotal) / 24;

    return collect(['average' => round($avg, 2)]);
}



public function hourBreakDown(){

$latestDate = DB::table('n_shifts')
                ->orderBy('date', 'desc')
                ->value('date');

return DB::table('reject_reasons')
                    ->join('n_shifts', 'n_shifts.id', '=', 'reject_reasons.shiftId')
                    ->where('n_shifts.date', '=', $latestDate)
                    ->groupBy('reject_reasons.hour', 'reject_reasons.shiftId', 'n_shifts.shiftType', 'n_shifts.date')
                    ->select('reject_reasons.hour', 'reject_reasons.shiftId', DB::raw('SUM(reject_reasons.count) as total_count'), 'n_shifts.shiftType', 'n_shifts.date')
                    ->get();
 
}

public function hourBreakDownQuntity() {
    $latestDate = DB::table('n_shifts')
        ->orderBy('date', 'desc')
        ->value('date');

    return DB::table('hours')
        ->join('n_shifts', 'n_shifts.id', '=', 'hours.shiftId')
        ->where('n_shifts.date', '=', $latestDate)
        ->groupBy('hours.quantity', 'hours.hour')
        ->select('hours.quantity', 'hours.hour')
        ->get();
}
}
