<?php

namespace App\Http\Controllers;
use App\Models\Hour;
use Illuminate\Http\Request;
use DB;

class HourController extends Controller
{
    public function getData()
    {
        $hourModel = new Hour();
        $data = $hourModel -> getHour();
        return response()->json($data);
    }

    public function addData(Request $req)
    {
        $testModel = new Hour();
        $result = $testModel->addHour($req->all());
        return response()->json($result);
    }
    
//delete
    function deleteData(Request $req)
    {
        $hourModel = new Hour();

        $id = $req->id;

      $del_result = $hourModel -> deleteData($id);

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
   //update
   public function getOneData(Request $req)
    {
        $hourModel = new Hour();
       $id = $req->id;

        $data = $hourModel -> getOneHour($id);
        return response()->json($data);
    }

   

    public function updateData(Request $req)
    {
        $json = array();
        $id = $req->id;
        $hourModel = new NShift();
        $result = $hourModel->updateHour($id, $req->all());
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

    public function create(Request $request){
        
        $result = DB::table('reject_reasons')->insert($request->all());
        return $result;

    }


      //get Reject
      public function getReject()
      {
           $rejectModel = new Hour();  
           $data = $rejectModel -> getReject();
          return response()->json($data);
      }



       //shift Time For DropDown
    public function getShiftTimeDropDown()
    {
        return DB::table('shifttimeforthedropdown')
        ->select('shifttimeforthedropdown.Shift', 'shifttimeforthedropdown.shiftType' )
        
        ->get();
    }

    //low production reason dropdown
    public function getLowReasonDropDown()
    {
        return DB::table('loproduction_reason')
        ->select('loproduction_reason.reason')
        
        ->get();
    }


}
