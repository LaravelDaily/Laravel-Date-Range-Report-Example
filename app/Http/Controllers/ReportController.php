<?php

namespace App\Http\Controllers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function dashboard_chart() {
        $daysBefore = 29;

        $startAt = CarbonImmutable::today()->subDays($daysBefore);

        $results = DB::table('tenants')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as anz'))
            ->where('created_at', '>=', $startAt)
            ->groupBy('date')
            ->get();

        $dates = collect(range(0, $daysBefore))
            ->mapWithKeys(function($item) use ($results, $startAt) {
                $currentDate = $startAt->addDays($item);
                $currentValue = $results->where('date', $currentDate->format('Y-m-d'))->first()->anz ?? 0;

                return [$currentDate->format('d.m.') => $currentValue];
            });

        dd($dates);
    }
}
