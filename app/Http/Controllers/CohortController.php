<?php

namespace App\Http\Controllers;

use App\Cohort;
use App\Http\Requests\StoreCohortRequest;
use App\Http\Requests\UpdateCohortRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CohortController extends Controller
{
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cohorts = Auth::user()->cohorts()->get()->toArray();
        return response()->json($cohorts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCohortRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCohortRequest $request)
    {
        $cohort = new Cohort();
        $cohort->name = $request->name;
        $cohort->institution()->associate(Auth::id());
        try {
            $cohort->save();
            $response = [
                'error' =>  false,
                'code'  =>  200,
                'reason'    =>  'Cohort created!'
            ];
        } catch (\Exception $exception) {
            $response = [
                'error' =>  true,
                'code'  =>  $exception->getCode(),
                'reason'    =>  'Cohort creation failed'
            ];
        }
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param Cohort $cohort
     * @return \Illuminate\Http\Response
     */
    public function show(Cohort $cohort)
    {
        return response()->json($cohort->toArray());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCohortRequest $request
     * @param Cohort $cohort
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCohortRequest $request, Cohort $cohort)
    {
        $cohort->name = $request->name;
        $cohort->institution()->associate(Auth::id());
        try {
            $cohort->save();
            $response = [
                'error' =>  false,
                'code'  =>  200,
                'reason'    =>  'Cohort updated!'
            ];
        } catch (\Exception $exception) {
            $response = [
                'error' =>  true,
                'code'  =>  $exception->getCode(),
                'reason'    =>  'Cohort update failed! Please try again'
            ];
        }
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Cohort $cohort
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cohort $cohort)
    {
        try {
            $cohort->delete();
            $response = [
                "error" => false,
                "code"  => 200,
                "reason" => "Cohort removed!"
            ];
        } catch (\Exception $exception) {
            $response = [
                "error" => true,
                "code"  => 500,
                "reason" => "Cohort removal failed!"
            ];
        }
        return response()->json($response);
    }
}
