<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestionAnswerRequest;
use App\QuestionAnswer;

class QuestionAnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(QuestionAnswer::all()->toArray());
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
     * @param StoreQuestionAnswerRequest $request
     * @return array
     */
    public function store(StoreQuestionAnswerRequest $request)
    {
        $answer = new QuestionAnswer();
        $answer->title = $request->input('title');
        $answer->question()->associate($request->input('question'));
        $answer->correct = (bool) $request->input('correct');

        try {
            $answer->save();
            session()->flash('success', 'Answer created!');
            return $response = [
                'error' => false,
                'code' => 201,
                'reason' => 'Answer created!'
            ];
        } catch (\Exception $exception) {
            logger($exception);
            session()->flash('error', 'Answer could not be created!');
            return $response = [
                'error' => false,
                'code' => 201,
                'reason' => 'Answer could not be created!'
            ];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $questionAnswer
     * @return \Illuminate\Http\Response
     */
    public function show(int $questionAnswer)
    {
        return response()->json(QuestionAnswer::find($questionAnswer)->toArray());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param QuestionAnswer $questionAnswer
     * @return \Illuminate\Http\Response
     */
    public function edit(QuestionAnswer $questionAnswer)
    {
        return $this->show($questionAnswer->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreQuestionAnswerRequest $request
     * @param QuestionAnswer $questionAnswer
     * @return array
     */
    public function update(StoreQuestionAnswerRequest $request, QuestionAnswer $questionAnswer)
    {
        $questionAnswer->title = $request->input('title');
        $questionAnswer->question()->associate($request->input('question'));
        $questionAnswer->correct = (bool) $request->input('correct');

        try {
            $questionAnswer->save();
            session()->flash('success', 'Answer updated!');
            return $response = [
                'error' => false,
                'code' => 200,
                'reason' => 'Answer updated!'
            ];
        } catch (\Exception $exception) {
            logger($exception);
            session()->flash('error', 'Answer could not be updated!');
            return $response = [
                'error' => false,
                'code' => 200,
                'reason' => 'Answer could not be updated!'
            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param QuestionAnswer $questionAnswer
     * @return array
     * @throws \Exception
     */
    public function destroy(QuestionAnswer $questionAnswer)
    {
        try {
            $questionAnswer->delete();
            session()->flash('success', 'Answer removed!');
            return $response = [
                'error' => false,
                'code' => 204,
                'reason' => 'Answer removed!'
            ];
        } catch (\Exception $exception){
            session()->flash('error', 'Answer could not be created!');
            logger($exception);
            return $response = [
                'error' => false,
                'code' => 201,
                'reason' => 'Answer could not be created!'
            ];
        }
    }
}
