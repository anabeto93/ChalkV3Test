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
        return $question
            ->setAttributes($request)
            ->store();
    }

    /**
     * @param Question $question
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAnswers(Question $question)
    {
        return $question->getAnswers();
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
        return $question
            ->setAttributes($request)
            ->store(true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Question $question
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Question $question)
    {
        return response()->json($question->remove());
    }
}
