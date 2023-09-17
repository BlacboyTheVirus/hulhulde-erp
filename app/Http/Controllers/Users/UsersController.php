<?php

namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax())
        {
            return $this->getUsers();
        }
        return view('users.index')->with(["roles" => Role::get()]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user)
    {

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email'
           // 'email' => 'required|email:rfc,dns|unique:users,email'
        ]);

        if($request->has('roles'))
        {
            $user->create($request->all())->roles()->sync($request->roles);
        }else{
            $user->create($request->all());
        }
        if($user)
        {
            return response(["success" => true, "message" => "User Created Successfully!"], 200);
        }
        return response(["success" => false, "message" => "User Creation Error!"], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', [
            "user" => $user,
            "userRole" => $user->roles->pluck('name')->toArray(),
            "roles" => Role::latest()->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
        ]);

        if($request->password != ""){
            $user->update($request->all());
        } else {
            $user->update([
                'name' => $request->name,
                'email'=> $request->email,
            ]);
;        }
        $user->roles()->sync($request->input('roles'));

        if($user)
        {
            return response(["success" => true, "message" => "User Update Successfully!"], 200);
        }
        return response(["success" => false, "message" => "User Update Error!"], 201);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user)
    {
        if($request->ajax() && $user->delete())
        {
            return response(["success" => true, "message" => "User Deleted Successfully!"], 200);
        }
        return response(["success" => false, "message" => "User Deletion Error!"], 201);
    }


    private function getUsers()
    {
        $data = User::with('roles')->get();
        return DataTables::of($data)
                ->addColumn('name', function($row){
                    return ucfirst($row->name);
                })
                ->addColumn('date', function($row){
                    return Carbon::parse($row->created_at)->format('d M, Y h:i:s A');
                })
                ->addColumn('roles', function($row){
                    $role = "";
                    if($row->roles != null)
                    {
                        foreach($row->roles as $next)
                        {
                            $role.='<span class="badge badge-primary">'.ucfirst($next->name).'</span> ';
                        }
                    }
                    return $role;
                })
                ->addColumn('action', function($row){
                    $action = "";
                    if($row->name != 'Superuser'){
                        //$action.="<a class='btn btn-xs btn-success' id='btnShow' href='".route('users.show', $row->id)."'><i class='fas fa-eye'></i></a> ";

                        if(Auth::user()->can('users.edit')){
                            $action.="<a class='btn btn-xs btn-warning' id='btnEdit' href='".route('users.edit', $row->id)."'><i class='fas fa-edit'></i></a>";
                        }

                        if(Auth::user()->can('users.destroy')){
                            $action.=" <button class='btn btn-xs btn-outline-danger' id='btnDel' data-id='".$row->id."'><i class='fas fa-trash'></i></button>";
                        }
                    }

                    return $action;
                })
                ->rawColumns(['name', 'date','roles', 'action'])->make('true');
    }


}
