<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NShift extends Model
{
    use HasFactory;

    public function getShift()
    {
        $result = DB::table('n_shifts')
        ->select('id','shiftType','date','carderCount','manPower','lowCarderCountReason','productType')
        ->latest('date')
        ->get();
        return $result;
    }

    public function addShift($data)
    {
        $result = DB::table('n_shifts')->insert($data);
        return $result;
    }

    public function deleteData($id)
    {
       $result = DB::table('n_shifts')-> where('id',$id)->delete();
       return $result;
    }

    public function getOneShift($id)
    {
        $result = DB::table('n_shifts')->select('id','shiftType','date','carderCount','manPower','lowCarderCountReason','productType')->where('id',$id)->get()->first();
        return $result;
    }

    public function updateShift($id, $data)
    {
        $result = DB::table('n_shifts')->where('id', $id)->update($data);
        return $result;
    }


    public function getOneReject($id)
    {
        $result = DB::table('reject_reasons')->select('id', 'hour', 'shiftId')->where('id',$id)->get()->first();
        return $result;
    }
   

}
