<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Question extends Model
{
    public function setQuizId(int $quiz)
    {
        $this->quiz()->associate($quiz);
        return $this;
    }

    public function setType(string $type)
    {
        $this->attributes['type'] = $type;
        return $this;
    }

    public function setFeedback(string $feedback)
    {
        $this->attributes['feedback'] = $feedback;
        return $this;
    }

    public function setTitle(string $title)
    {
        $this->attributes['title'] = $title;
        return $this;
    }

    public function setAttributes(Request $request)
    {
        $this->setType($request->input('type'))
            ->setTitle($request->input('title'))
            ->setQuizId($request->input('quiz'))
            ->setFeedback($request->input('feedback', null));
        return $this;
    }
    /**
     * The Quiz the Question belongs to.
     */
    public function quiz() {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getQuiz()
    {
        $response =  $this
            ->quiz()
            ->first()
            ->toArray();
        return response()->json($response);
    }

    /**
     * The Answers that belong to the Question.
     */
    public function questionAnswers() {
        return $this->hasMany(QuestionAnswer::class);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAnswers()
    {
        $response = $this
            ->questionAnswers()
            ->get()
            ->toArray();
        return response()->json($response);
    }

    /**
     * @param bool $update
     * @return array
     */
    public function store(bool $update = false)
    {
        try {
            $this->save();
            session()->flash('success', 'Question created!');
            return response()->json([
                "error" =>  false,
                "code"  =>  201,
                "reason"    =>  ($update)? "Question updated!" : "Question created!"
            ]);
        } catch (\Exception $exception) {
            logger($exception);
            session()->flash('error', 'Question could not be created!');
            return response()->json([
                "error" =>  true,
                "code"  =>  500,
                "reason"    =>  ($update)? "Question could not be updated!" : "Question could not be created!"
            ]);
        }
    }

    /**
     * @return array
     */
    public function remove()
    {
        try {
            $this->delete();
            session()->flash('success', 'Question removed!');
            return [
                "error" =>  false,
                "code"  =>  204,
                "reason"    =>  "Question removed"
            ];
        } catch (\Exception $exception) {
            return [
                "error" =>  false,
                "code"  =>  204,
                "reason"    =>  "Question could not be removed"
            ];
        }
    }
}
