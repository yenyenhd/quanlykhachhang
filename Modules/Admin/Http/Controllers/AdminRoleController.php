<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Requests\RequestRole;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Traits\Delete;

class AdminRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    use Delete;
    public function __construct()
    {
        $this->middleware('role:admin');
    }
    public function index()
    {
        $roles = Role::all();
        return view('admin::role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $permissionParent = Permission::where('parent_id', 0)->get();
        return view('admin::role.add', compact('permissionParent'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(RequestRole $request)
    {
        $role = Role::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
        ]);
        $data = $request->all();
        $roles= $role->syncPermissions($data['permission_id']);
        return redirect('role/create')->with('message', 'Thêm vai trò thành công');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $permissionParent = Permission::where('parent_id', 0)->get();
        $role = Role::find($id);
        $permissionChecked = $role->permissions;
        return view('admin::role.edit', compact('permissionParent', 'role', 'permissionChecked'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        Role::find($id)->update([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
        ]);
        $role = Role::find($id);
        $data = $request->all();
        $roles= $role->syncPermissions($data['permission_id']);
        return redirect('role')->with('message', 'Update vai trò thành công');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $role = Role::find($id);
        $this->deleteTrait($id, $role );
        return redirect('role');
        
    }
}
