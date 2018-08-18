<?php

namespace App\Http\Controllers;

use App\Course;
use App\Http\Requests\StoreCourseRequest;
use App\User;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = new User();
        $courses = $user->courses()->get()->toArray();
        return response()->json($courses);
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
     * @param StoreCourseRequest $request
     * @return array
     */
    public function store(StoreCourseRequest $request)
    {
        $course = new Course();
        $course->name = $request->name;
        $course->institution()->associate($request->institution);
        $course->description = $request->description;
        $course->teacher = $request->teacher;
        $course->enabled = (bool) $request->enabled;

        try {
            $course->save();
            $response = [
                "error" =>  false,
                "code"  =>  201,
                "reason"    =>  'Course created!'
            ];
        } catch (\Exception $exception) {
            $response = [
                "error" =>  true,
                "code"  =>  500,
                "reason"    =>  $exception->getMessage()
            ];
        }
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param Course $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        return response()->json($course->toArray());
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
     * @param StoreCourseRequest $request
     * @param Course $course
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreCourseRequest $request, Course $course)
    {
        $course->name = $request->name;
        $course->institution()->associate($request->institution);
        $course->description = $request->description;
        $course->teacher = $request->teacher;
        $course->enabled = (bool) $request->enabled;

        try {
            $course->save();
            $response = [
                "error" =>  false,
                "code"  =>  200,
                "reason"    =>  'Course updated!'
            ];
        } catch (\Exception $exception) {
            $response = [
                "error" =>  true,
                "code"  =>  500,
                "reason"    =>  'Course update failed!'
            ];
        }
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Course $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        try {
            $course->delete();
            session()->flash('success', 'Course removed!');
            $response = [
                "error" => false,
                "code" => 204,
                "reason" => "Course removed!"
            ];
        } catch (\Exception $exception) {
            logger($exception);
            session()->flash('success', 'Course could not be removed!');
            $response = [
                "error" => false,
                "code" => 204,
                "reason" => "Course could not be removed!"
            ];
        }
        return response()->json($response);
    }
}
