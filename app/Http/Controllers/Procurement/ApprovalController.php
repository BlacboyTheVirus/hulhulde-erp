<?php

namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Procurement\Approval;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function edit(Approval $approval)
    {
        return $approval;
    }

    public function update(Request $request){

        if ($request->status){
            $request->merge(['status' => true]);
        } else {
            $request->merge(['status' => false]);
        }
        $request->merge(['user_id' => auth()->id()]);

        $approval = Approval::find($request->id)->update($request->all());

        if ($approval){
            return response(["success"=> true, "message" => "Approval Updated successfully."], 200);
        } else {
            return response(["success"=> false, "message" => "Approval not updated!"], 200);
        }

    }
}
