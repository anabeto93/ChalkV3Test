<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::post('admins/register', 'Auth\RegisterController@createAdmin');
Route::post('admins/institutions.add', 'AdminController@institution');
//Route::resources([
//    'admins'    => 'AdminController',
//    'cohorts'   => 'CohortController',
//    'institutions'  =>  'InstitutionController',
//    'courses'   =>  'CourseController',
//    'questions' =>  'QuestionController'
//]);

Route::get('graphql/test/tests', function (){
    return response()->json(['message' => 'welcome']);
})->middleware('auth.student');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/**
 * Course routes
 */
//Route::prefix('courses')->name('course.')->group(function () {
//    Route::get('/', 'CourseController@index')->name('list');
//    Route::get('create', 'CourseController@create')->name('create');
//    Route::get('update/{id}', 'CourseController@update')->name('update');
//    Route::get('{id}', 'CourseController@show')->name('show');
//});
Route::prefix('courses')->name('course.')->group(function () {
    Route::get('/', function () {
        return view('course.list', ['courses' => \App\Course::all()]);
    })->name('list');
    
    Route::get('create', function () {
        return view('course.createOrUpdate');
    })->name('create');

	/**
	 * ID Prefix (Course)
	 */
    Route::prefix('{course}')->group(function () {
        Route::get('/', function ($course) {
            return view('course.show', ['course' => \App\Course::find($course)]);
        })->name('show');

        Route::get('update', function ($course) {
            return view('course.createOrUpdate', ['course' => \App\Course::find($course)]);
        })->name('update');

        /**
         * Course Users (Show and Assign)
         */
        Route::prefix('users')->name('user.')->group(function () {
            Route::get('/', function ($course) {
                return view('course.user.list', ['course' => \App\Course::find($course)]);
            })->name('list');
            
            Route::get('assign', function ($course) {
                return view('course.user.assign', ['course' => \App\Course::find($course), 'users' => \App\User::all
                ()]);
            })->name('assign');
        });
        
        /**
         * Folder routes
         */
        Route::prefix('folders')->name('folder.')->group(function () {
            Route::get('/', function ($course) {
                $course = \App\Course::find($course);
                return view('course.folder.list', [
                    'course' => $course,
                    'folders' => $course->folders
                ]);
            })->name('list');
            
            Route::get('create', function ($course) {
                return view('course.folder.createOrUpdate', [
                    'course' => \App\Course::find($course)
                ]);
            })->name('create');
            
            Route::get('{folder}/update', function ($course, $folder) {
                return view('course.folder.createOrUpdate', [
                    'course' => \App\Course::find($course),
                    'folder' => \App\Folder::find($folder)
                ]);
            })->name('update');
        });

        /**
         * Session routes
         */
        Route::prefix('sessions')->name('session.')->group(function () {
            Route::get('/', function ($course) {
                $course = \App\Course::find($course);
                return view('course.session.list', [
                    'course' => $course,
                    'sessions' => $course->sessions
                ]);
            })->name('list');
            
            Route::get('create', function ($course) {
                return view('course.session.createOrUpdate', [
                    'course' => \App\Course::find($course)
                ]);
            })->name('create');
            
            Route::get('{session}/update', function ($course, $session) {
                return view('course.session.createOrUpdate', [
                    'course' => \App\Course::find($course),
                    'session' => \App\Session::find($session)
                ]);
            })->name('update');
        });
    });
});

/**
 * User routes
 */
Route::prefix('users')->name('user.')->group(function () {
    Route::get('/', function () {
        return view('user.list', ['users' => \App\User::all()]);
    })->name('list');
    
    Route::get('create', function () {
        return view('user.createOrUpdate');
    })->name('create');

    /**
	 * ID Prefix (User)
	 */
    Route::prefix('{user}')->group(function () {
        Route::get('/', function ($user) {
            return view('user.show', ['user' => \App\User::find($user)]);
        })->name('show');

        Route::get('update', function ($user) {
            return view('user.createOrUpdate', ['user' => \App\User::find($user)]);
        })->name('update');

        /**
         * User Courses (Show and Assign)
         */
        Route::prefix('courses')->name('course.')->group(function () {
            Route::get('/', function ($user) {
                return view('user.course.list', ['user' => \App\User::find($user)]);
            })->name('list');
            
            Route::get('assign', function ($user) {
                return view('user.course.assign', ['user' => \App\User::find($user), 'courses' => \App\Course::all()]);
            })->name('assign');
        });
    });
});

/**
 * Cohort routes
 */
Route::prefix('cohorts')->name('cohort.')->group(function () {
    Route::get('/', function () {
        return view('cohort.list', ['cohorts' => \App\Cohort::all()]);
    })->name('list');
    
    Route::get('create', function () {
        return view('cohort.createOrUpdate');
    })->name('create');

	/**
	 * ID Prefix (Cohort)
	 */
    Route::prefix('{cohort}')->group(function () {
        Route::get('/', function ($cohort) {
            return view('cohort.show', ['cohort' => \App\Cohort::find($cohort)]);
        })->name('show');

        Route::get('update', function ($cohort) {
            return view('cohort.createOrUpdate', ['cohort' => \App\Cohort::find($cohort)]);
        })->name('update');

        /**
         * Cohort Users (Show and Assign)
         */
        Route::prefix('users')->name('user.')->group(function () {
            Route::get('/', function ($cohort) {
                return view('cohort.user.list', ['cohort' => \App\Cohort::find($cohort)]);
            })->name('list');
            
            Route::get('assign', function ($cohort) {
                return view('cohort.user.assign', ['cohort' => \App\Cohort::find($cohort), 'users' => \App\User::all()]);
            })->name('assign');
        });

        /**
         * Cohort Courses (Show and Assign)
         */
        Route::prefix('courses')->name('course.')->group(function () {
            Route::get('/', function ($cohort) {
                return view('cohort.course.list', ['cohort' => \App\Cohort::find($cohort)]);
            })->name('list');
            
            Route::get('assign', function ($cohort) {
                return view('cohort.course.assign', ['cohort' => \App\Cohort::find($cohort), 'courses' => \App\Course::all()]);
            })->name('assign');
        });
    });
});

/**
 * Quiz routes
 */
Route::prefix('quizzes')->name('quiz.')->group(function () {
    Route::get('/', function () {
        return view('quiz.list', ['quizzes' => \App\Quiz::all()]);
    })->name('list');
    
    Route::get('create', function () {
        return view('quiz.createOrUpdate');
    })->name('create');
    
	
	/**
	 * ID Prefix (Quiz)
	 */
    Route::prefix('{quiz}')->group(function () {
        Route::get('/', function ($quiz) {
            return view('quiz.show', ['quiz' => \App\Quiz::find($quiz)]);
        })->name('show');

        Route::get('update', function ($quiz) {
            return view('quiz.createOrUpdate', ['quiz' => \App\Quiz::find($quiz)]);
        })->name('update');

        /**
         * Questions
         */
        Route::prefix('questions')->name('question.')->group(function () {
            Route::get('/', function ($quiz) {
                return view('quiz.question.list', ['quiz' => \App\Quiz::find($quiz)]);
            })->name('list');

            Route::get('create', function ($quiz) {
                return view('quiz.question.createOrUpdate', ['quiz' => \App\Quiz::find($quiz)]);
            })->name('create');

            /**
             * ID Prefix (Question)
             */
            Route::prefix('{question}')->group(function () {
                Route::get('/', function ($quiz) {
                    return view('quiz.question.show', ['quiz' => \App\Quiz::find($quiz)]);
                })->name('show');

                Route::get('update', function ($quiz, $question) {
                    return view('quiz.question.createOrUpdate', [
                        'quiz' => \App\Quiz::find($quiz),
                        'question' => \App\Question::find($question)
                    ]);
                })->name('update');

            });
        });

        /**
         * QuizResults
         */
        Route::prefix('results')->name('result.')->group(function () {
            Route::get('/', function ($quiz) {
                return view('quiz.result.list', ['quiz' => \App\Quiz::find($quiz)]);
            })->name('list');

            Route::get('{quizResult}', function ($quiz, $quizResult) {
                return view('quiz.result.show', ['quiz' => \App\Quiz::find($quiz), 'quizResult' =>
                    \App\QuizResult::find($quizResult)]);
            })->name('show');
        });
    });
});