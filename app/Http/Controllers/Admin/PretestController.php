<?php

namespace App\Http\Controllers\Admin;

use App\Enums\QuestionDifficulty;
use App\Enums\QuestionType;
use App\Enums\TestType;
use App\Http\Controllers\Controller;
use App\Http\Requests\BulkQuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Course;
use App\Models\Question;
use Inertia\Inertia;

class PretestController extends Controller
{
    //
    public function index(){
        $query = Question::with(['course'])->where('test_type','Pretest');

        if ($search = request('question')) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(question) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(answer) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }
        $sort = request()->query('sort', '');
        $sortField = $sortDirection = null;

        // Only split if $sort is not empty
        if (!empty($sort)) {
            [$sortField, $sortDirection] = explode(':', $sort);

            if (!in_array($sortDirection, ['asc', 'desc'])) {
                $sortDirection = null;
            }
        }
        

        if ($courseTitle = request('course')) {
            $query->whereHas('course', function ($q) use ($courseTitle) {
                $q->where('title', 'like', '%' . $courseTitle . '%');
            });
        }

        if ($difficulty = request('difficulty')) {
            $query->where('difficulty_type', $difficulty);
        }

        if ($question_type = request('question_type')) {
            $query->where('question_type', $question_type);
        }

        if (!empty($sortField) && !empty($sortDirection)) {
            $query->orderBy($sortField, $sortDirection);
        }

        $perPage = request('items', 5);
        $questions = $query->paginate($perPage)->onEachSide(1);

        $title = Course::select('title')->distinct()->pluck('title');
        $difficulty = QuestionDifficulty::cases();
        $questionTypes = collect(QuestionType::cases())->map(function ($case) {http://localhost/admin/pretest
            return $case->value;
        })->toArray();

        $testTypes = collect(TestType::cases())->map(function ($case) {
            return $case->value;
        })->toArray();

        // Add all filters to the filters array
        $filters = [
            'courses' => $title,
            'difficulty' => $difficulty,
            'question_type' => $questionTypes,
            'test_type' => $testTypes,	
        ];
        
        return Inertia::render('Admin/Questions/Pretest', [
            'title' => 'Admin Pretest',
            'questions' => QuestionResource::collection($questions), 
            'filters' => $filters,
            'queryParams' => request()->query() ?: null,
        ]);
    }

    public function show(){
        $query = Question::with(['course'])->where('test_type','Test');

        if ($search = request('question')) {
            $search = strtolower($search); // Convert the search term to lowercase
        
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(question) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(answer) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }
        
        if ($courseTitle = request('course')) {
            $query->whereHas('course', function ($q) use ($courseTitle) {
                $q->where('title', 'like', '%' . $courseTitle . '%');
            });
        }

        if ($difficulty = request('difficulty')) {
            $query->where('difficulty_type', $difficulty);
        }
        if ($question_type = request('question_type')) {
            $query->where('question_type', $question_type);
        }
        
        

        $perPage = request('items', 5);
        $questions = $query->paginate($perPage)->onEachSide(1);

        $title = Course::select('title')->distinct()->pluck('title');
        $difficulty = array_map(fn($case) => $case->value, QuestionDifficulty::cases());
        $questionTypes = collect(QuestionType::cases())->map(function ($case) {
            return $case->value;
        })->toArray();

        // Add all filters to the filters array
        $filters = [
            'courses' => $title,
            'difficulty' => $difficulty,
            'question_type' => $questionTypes,
        ];
        
        return Inertia::render('Admin/Questions/AddPretest',[
            'title' => 'Admin Pretest',
            'questions' => QuestionResource::collection($questions),
            'filters' => $filters,
            'queryParams' => request()->query() ?: null,
        ]);
        
    }
    public function add(BulkQuestionRequest $request)
    {
        $validated = $request->validated();

        foreach ($validated['questions'] as $id) { 
            $question = Question::find($id);

            if ($question) {
                $question->test_type = TestType::PRETEST->value;
                $question->save();
            }
        }

        return redirect()->route('admin.pretest.index');
    }

}
