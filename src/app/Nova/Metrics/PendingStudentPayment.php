<?php

namespace App\Nova\Metrics;

use App\Models\Content;
use Carbon\Carbon;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;

class PendingStudentPayment extends Value
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $query = Content::query();

        // Adjust the query based on the selected range
        if ($request->range === 'TODAY') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($request->range === 'MTD') {
            $query->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year);
        } elseif ($request->range === 'QTD') {
            $query->whereQuarter('created_at', Carbon::now()->quarter)
                ->whereYear('created_at', Carbon::now()->year);
        } elseif ($request->range === 'YTD') {
            $query->whereYear('created_at', Carbon::now()->year);
        }

        $price = $query->sum('price');
        $paid = $query->sum('paid');

        return $this->result($price-$paid)->currency('£');
    }


    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            'ALL' => 'All Time',
            'TODAY' => __('Today'),
            'MTD' => __('Month To Date'),
            'QTD' => __('Quarter To Date'),
            'YTD' => __('Year To Date'),
        ];
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'pending-student-payment';
    }
}
