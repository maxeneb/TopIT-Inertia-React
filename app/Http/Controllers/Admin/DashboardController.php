<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PdfResource;
use App\Http\Resources\StudentResource;
use App\Http\Resources\UserResource;
use App\Models\Course;
use App\Models\Pdf;
use App\Models\Question;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $year = $request->input('year', date('Y')); 

        $monthlyCounts = User::where('userable_type', 'App\\Models\\Student')
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->when($year, function ($query) use ($year) {
                $query->whereYear('created_at', $year);
            })
            ->groupBy('year', 'month')
            ->get()
            ->pluck('count', 'month');

        $questionCounts = Question::selectRaw('test_type, COUNT(*) as total')
            ->whereIn('test_type', ['Pretest', 'Test'])
            ->groupBy('test_type')
            ->pluck('total', 'test_type');

        $cards = [
            'Total Students' => Student::count(),
            'Total Courses' => Course::count(),
            'Test Questions' => $questionCounts['Test'] ?? 0,
            'Pretest Questions' => $questionCounts['Pretest'] ?? 0,
        ];

        $chartData = $this->prepareChartData($monthlyCounts);

        $pdfs = Pdf::with('course')->orderBy('updated_at', 'desc')->take(3)->get();

        return Inertia::render(
            'Admin/Dashboard',
            [
                'title' => 'Admin Dashboard',
                'chartData' => $chartData,
                'cards' => $cards,
                'pdfs' => PdfResource::collection($pdfs),
                'initialYear' => $year,
            ]
        );
    }
    private function prepareChartData($monthlyCounts)
    {
        $filledMonthlyCounts = array_fill(1, 12, 0);

        foreach ($monthlyCounts as $month => $count) {
            $filledMonthlyCounts[$month] = $count;
        }

        return [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'data' => array_values($filledMonthlyCounts),
        ];
    }
}
