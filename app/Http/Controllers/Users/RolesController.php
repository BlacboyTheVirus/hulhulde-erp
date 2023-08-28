<?php

namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;


class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax())
        {
            return $this->getRoles();
        }
        return view('users.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::get(); 
        return view('users.roles.create')->with(['permissions'=> $permissions]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {        
        //Validate name
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name', 
            'permission' => 'required'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errortext = "";
            foreach ($errors as $err){
                $errortext .= $err . " "; 
            }
            return response(["success"=> false, "message" => $errortext], 200);
        }
     
        $role = Role::create(['name' => strtolower(trim($request->name))]);
        $role->syncPermissions($request->permission);
        if($role)
        {
            return response(["success" => true, "message" => "New Role Added Successfully!"], 200);
        } else {
            return response(["success" => false, "message" => "Error saving new role!"], 200);
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Role $role)
    {
        if($request->ajax())
        {
            return $this->getRolesPermissions($role);
        }
        return view('users.roles.show')->with(['role' => $role]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return view('users.roles.edit')->with(['role' => $role]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Role $role, Request $request)
    {
        
        //Validate name
         $validator = Validator::make($request->all(), [
            'name' => 'required', 
            'permission' => 'required'
        ]);

        $role->update($request->only('name'));
        $role->syncPermissions($request->permission);
        if($role)
        {
            return response(["success" => true, "message" => "Role Updated Successfully!"], 200);
        }
        return response(["success" => false, "message" => "Error on updating Role!"], 200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Role $role)
    {
        if($request->ajax() && $role->delete())
        {
            return response(["success" => true, "message" => "Role Deleted Successfully!"], 200);
        }
        return response(["success" => false, "message" => "Error Deleting Role!"], 200);
    }


    private function getRoles()
    {
        $data = Role::withCount(['users', 'permissions'])->get(); 
        return DataTables::of($data)
                ->addColumn('name', function($row){
                    return ucfirst($row->name);
                })
                ->addColumn('users_count', function($row){
                    return $row->users_count;
                })
                ->addColumn('permissions_count', function($row){
                    return $row->permissions_count;
                })
                ->addColumn('action', function($row){
                    $action = ""; 
                    $action.="<a class='btn btn-xs btn-success' id='btnShow' href='".route('users.roles.show', $row->id)."'><i class='fas fa-eye'></i></a> "; 
                    $action.="<a class='btn btn-xs btn-warning' id='btnEdit' href='".route('users.roles.edit', $row->id)."'><i class='fas fa-edit'></i></a>"; 
                    if($row->name != 'superuser'){ 
                        $action.=" <button class='btn btn-xs btn-outline-danger' id='btnDel' data-id='".$row->id."'><i class='fas fa-trash'></i></button>";
                    } 
                    return $action;
                })
                ->make('true');
    }

    private function getRolesPermissions($role)
    {
        $permissions = $role->permissions; 
        return DataTables::of($permissions)->make('true');
    }


}
