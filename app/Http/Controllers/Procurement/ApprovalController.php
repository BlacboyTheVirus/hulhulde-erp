<?php

namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Procurement\Approval;
use App\Models\Procurement\Quality;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function edit(Approval $approval)
    {
        $rec_price = Quality::where('procurement_id', '=', $approval->id)->value('recommended_price');

        $price = ($approval->approved_price ? $approval->approved_price : $rec_price );

        $new_array =  $approval->toArray();
        $new_approval = array_merge($new_array, ['price' => $price]);
        return $new_approval;
    }

    public function update(Request $request){

        if ($request->status){
            $request->merge(['status' => true]);
        } else {
            $request->merge(['status' => false]);
        }
        $request->merge(['user_id' => auth()->id()]);

        //update approval
        $approval = Approval::find($request->id)->update($request->all());

        // update price
        //Quality::where('procurement_id', '=', $request->id)->update(['recommended_price' => $request->approved_price ]);


        if ($approval){
            return response(["success"=> true, "message" => "Approval Updated successfully."], 200);
        } else {
            return response(["success"=> false, "message" => "Approval not updated!"], 200);
        }

    }
}
