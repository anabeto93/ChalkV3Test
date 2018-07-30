<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInstitutionRequest;
use App\Http\Requests\UpdateInstitutionRequest;
use App\Institution;

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
    public function store(StoreInstitutionRequest $request): array
    {
        $institution = new Institution($request->input('name'));
        return $institution->store();
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
            $response = [
                'error' => true,
                'code' => $exception->getCode(),
                'reason' => $exception->getMessage()
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
            return [
                'error' => false,
                'code' => 205,
                'reason' => 'Institution deleted!'
            ];
        } catch (\Exception $exception) {
            return [
                'error' => true,
                'code' => $exception->getCode(),
                'reason' => $exception->getMessage()
            ];
        }
    }
}
