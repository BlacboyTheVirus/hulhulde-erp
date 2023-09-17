<?php

namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @noinspection PhpUnused
     */
    public function index(Request $request)
    {
        if($request->ajax())
        {
           return $this->getPermissions($request->role_id);
        }
        return view('users.permissions.index');
    }

    /**
     * Show the form for creating a new resource.
     * @noinspection PhpUnused
     * @noinspection PhpUnused
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @noinspection PhpUnused
     */
    public function store(Request $request)
    {
        //Validate name
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:permissions,name'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errortext = "";
            foreach ($errors as $err){
                $errortext .= $err . " ";
            }
            return response(["success"=> false, "message" => $errortext], 200);
        }

        $permission = Permission::create(["name" => strtolower(trim($request->name))]);
        if($permission)
        {
            return response(["success" => true, "message" => "Permission created successfully"], 200);
        } else {
            return response(["success" => false, "message" => "Permission creation error!"], 200);
        }


    }
    /**
     * Display the specified resource.
     * @noinspection PhpUnused
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @noinspection PhpUnused
     */
    public function edit(Permission $permission)
    {
        return view('users.permissions.edit')->with(['permission'=>$permission]);
    }

    /**
     * Update the specified resource in storage.
     * @noinspection PhpUnused
     */
    public function update(Request $request, Permission $permission)
    {
         //Validate name
         $validator = Validator::make($request->all(), [
            "name" => 'required|unique:permissions,name,'.$permission->id
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errortext = "";
            foreach ($errors as $err){
                $errortext .= $err . " ";
            }
            return response(["success"=> false, "message" => $errortext], 200);
        }

        $permission = Permission::find($request->id);

        if($permission->update($request->only('name')))
        {
            return response(["success" => true, "message" => "Permission Updated Successfully"], 200);

        }
        return response(["success" => false, "message" => "Permission Update failed"], 200);
    }

    /**
     * Remove the specified resource from storage.
     * @noinspection PhpUnused
     */
    public function destroy(Request $request, Permission $permission)
    {

        if($request->ajax() && $permission->delete())
        {
            return response(["success" => true, "message" => "Permission Deleted Successfully"], 200);
        }
        return response(["success" => false, "message" => "Data Delete Error! Please Try again"], 200);
    }




    private function getPermissions($role_id)
    {
        $data = Permission::get();
        return DataTables::of($data, $role_id)
            ->addColumn('chkBox', function($row) use ($role_id){
                if($row->name=="dashboard")
                {
                    //force dashboard to be selected
                    return "<input type='checkbox' name='permission[".$row->name."]'  value=".$row->name." checked onclick='return false;' class='permission' > ";
                }else{

                    if( $role_id !="" )
                    {
                        $role= Role::where('id', $role_id)->first();
                        $rolePermissions = $role->permissions->pluck('name')->toArray();
                        if(in_array($row->name, $rolePermissions))
                        {
                            return "<input type='checkbox' name='permission[".$row->name."]' value=".$row->name." checked  class='permission'> ";
                        }
                    }
                    return "<input type='checkbox' name='permission[".$row->name."]' value=".$row->name." class='permission'>";
                }
            })
            ->addColumn('action', function($row){
                $action = "";
                $action.="<a class='btn btn-xs btn-warning' id='btnEdit' href='".route('users.permissions.edit', $row->id)."'><i class='fas fa-edit'></i></a>";
                $action.=" <button class='btn btn-xs btn-outline-danger' id='btnDel' data-id='".$row->id."'><i class='fas fa-trash'></i></button>";
                return $action;
            })
        ->rawColumns(['chkBox', 'action'])->make(true);
    }






}
