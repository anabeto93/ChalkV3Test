<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInstitutionRequest;
use App\Http\Requests\UpdateInstitutionRequest;
use App\Institution;
use phpDocumentor\Reflection\Types\Resource_;

class InstitutionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Institution::all()->toArray());
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
     * @param StoreInstitutionRequest $request
     * @return array
     */
    public function store(StoreInstitutionRequest $request)
    {
        $institution = new Institution();
        $institution->name = $request->name;
        try {
            $institution->save();
            $response = [
                'status' => 'success',
                'code' => 200,
                'reason' => 'Institution created!'
            ];
        } catch (\Exception $exception) {
            logger($exception);
            session()->flash('error', 'Institution could not be created!');
            $response = [
                'error' => true,
                'code' => 500,
                'reason' => 'Institution could not be created!'
            ];
        }
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param Institution $institution
     * @return \Illuminate\Http\Response
     */
    public function show(Institution $institution)
    {
        return response()->json($institution->toArray());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Institution $institution
     * @return \Illuminate\Http\Response
     */
    public function edit(Institution $institution)
    {
        return response()->json($institution->toArray());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateInstitutionRequest $request
     * @param Institution $institution
     * @return array
     */
    public function update(UpdateInstitutionRequest $request, Institution $institution)
    {
        $institution->name = $request->input('name');
        try {
            $institution->save();
            $response = [
                'error' => false,
                'code' => 200,
                'reason' => 'Institution updated!'
            ];
        } catch (\Exception $exception) {
            logger($exception);
            session()->flash('error', 'Institution could not be created!');
            $response = [
                'error' => true,
                'code' => 500,
                'reason' => 'Institution could not be updated!'
            ];
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Institution $institution
     * @return array
     * @throws \Exception
     */
    public function destroy(Institution $institution)
    {
        try {
            $institution->delete();
            session()->flash('success', 'Institution deleted!');
            $response = [
                'error' => false,
                'code' => 205,
                'reason' => 'Institution deleted!'
            ];
        } catch (\Exception $exception) {
            logger($exception);
            session()->flash('error', 'Institution could not be deleted!');
            $response = [
                'error' => true,
                'code' => $exception->getCode(),
                'reason' => 'Institution could not be deleted!'
            ];
        }
        return response()->json($response);
    }
}
