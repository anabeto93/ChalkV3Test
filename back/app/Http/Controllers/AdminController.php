<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Http\Requests\ChangeAdminPasswordRequest;
use App\Http\Requests\StoreAdminRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAdminRequest $request)
    {
        $admin = new Admin();
        return $admin->store();
    }

    public function institution(Request $request)
    {
        return Admin::addInstitutions($request->user()->id, $request->input('institution_id'));
    }

    /**
     * Display the specified resource.
     *
     * @param Admin $admin
     * @return Admin
     */
    public function show(Admin $admin)
    {
        return $admin;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Admin $admin
     * @return Admin
     */
    public function edit(Admin $admin)
    {
        return $admin;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $admin = new Admin();
        return $admin->updateRecord($id, $request);
    }

//    public function changePassword(ChangeAdminPasswordRequest $request, $id)
//    {
//        return Admin::changePassword($id, $request->input('password'), $request->input('new_password'));
//    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
