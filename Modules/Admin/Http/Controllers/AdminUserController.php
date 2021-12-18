<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Requests\RequestUser;
use Illuminate\Routing\Controller;
use App\Models\User;
use App\Models\Store;

use Hash;
use App\Traits\Delete;
use App\Traits\StorageImage;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    use StorageImage;
    use Delete;


    public function __construct()
    {
        $this->middleware('role:admin');
    }
    public function index()
    {
        $users = User::all();
        return view('admin::user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $roles = Role::all();
        $list_stores = Store::all();
        return view('admin::user.add', compact('roles', 'list_stores'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(RequestUser $request)
    {
        $dataInsert = [
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'store_id' => $request->store_id,
            'password' => Hash::make($request->password),
        ];
        $user = User::create($dataInsert);
        $data = $request->all();
        $user->assignRole($data['role_id']);

        return redirect('user/create')->with('message', 'Thêm user thành công');
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
        $user = User::find($id);
        $roles = Role::all();
        $list_stores = Store::all();
        $roleOfUser = $user->roles;
        return view('admin::user.edit', compact('user', 'roles', 'roleOfUser','list_stores' ));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $dataUpdate = [
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'store_id' => $request->store_id,
            'password' => Hash::make($request->password),
        ];
        User::find($id)->update($dataUpdate);
        $user = User::find($id);
        $data = $request->all();
        $user->syncRoles($data['role_id']);
        return redirect('user')->with('message', 'Update user thành công');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $this->deleteTrait($id, $user);
        return redirect('user');
    }
}
