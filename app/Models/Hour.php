<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Hour extends Model
{
    use HasFactory;

    public function getHour()
    {
        $result = DB::table('hours')->select('shiftId','hour','quantity','reasonForLowProductions')->get();
        return $result;
    }

    public function addHour($data)
    {
        // dd(data);
        $result = DB::table('hours')->insert($data);
        return $result;
        
    }

    public function deleteData($id)
    {
       $result = DB::table('n_shifts')-> where('id',$id)->delete();
       return $result;
    }

    public function getOneHour($id)
    {
        $result = DB::table('hours')->select('id','shiftId','hour','quantity','reasonForLowProductions')->where('id',$id)->get()->first();
        return $result;
    }

    public function updateHour($id, $data)
    {
        $result = DB::table('hours')->where('id', $id)->update($data);
        return $result;
    }

    //get reject
    public function getReject()
    {
        $result = DB::table('reject_reasons')->select('id', 'hour','shiftId','reason', 'count', 'date')->get();
        return $result;
    }


   


    

}
