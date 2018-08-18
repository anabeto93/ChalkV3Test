<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestionRequest;
use App\Question;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Question::all()->toArray());
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
     * @param StoreQuestionRequest $request
     * @return array
     */
    public function store(StoreQuestionRequest $request)
    {
        $question = new Question();
        $question->type = $request->input('type');
        $question->title = $request->input('title');
        $question->quiz()->associate($request->input('quiz'));
        $question->feedback = $request->input('feedback', null);
        try {
            $question->save();
            session()->flash('success', 'Question created!');
            return response()->json([
                "error" =>  false,
                "code"  =>  201,
                "reason"    =>  "Question created!"
            ]);
        } catch (\Exception $exception) {
            logger($exception);
            session()->flash('error', 'Question could not be created!');
            return response()->json([
                "error" =>  true,
                "code"  =>  500,
                "reason"    =>  "Question could not be created!"
            ]);
        }
    }

    /**
     * @param Question $question
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAnswers(Question $question)
    {
        return response()->json($question->questionAnswers()->get()->toArray());
    }

    /**
     * Display the specified resource.
     *
     * @param Question $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        return response()->json($question->toArray());
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
     * @param StoreQuestionRequest $request
     * @param Question $question
     * @return array
     */
    public function update(StoreQuestionRequest $request, Question $question)
    {
        $question->type = $request->input('type');
        $question->title = $request->input('title');
        $question->quiz()->associate($request->input('quiz'));
        $question->feedback = $request->input('feedback', null);
        try {
            $question->save();
            session()->flash('success', 'Question updated!');
            return response()->json([
                "error" =>  false,
                "code"  =>  201,
                "reason"    =>  "Question updated!"
            ]);
        } catch (\Exception $exception) {
            logger($exception);
            session()->flash('error', 'Question could not be updated!');
            return response()->json([
                "error" =>  true,
                "code"  =>  500,
                "reason"    =>  "Question could not be updated!"
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Question $question
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Question $question)
    {
        try {
            $question->delete();
            session()->flash('success', 'Question removed!');
            return response()->json([
                "error" =>  false,
                "code"  =>  204,
                "reason"    =>  "Question removed"
            ]);
        } catch (\Exception $exception) {
            return response([
                "error" =>  false,
                "code"  =>  204,
                "reason"    =>  "Question could not be removed"
            ]);
        }
    }
}
