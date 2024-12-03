<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Worker;
use Illuminate\Http\Request;

class ReportController extends Controller
{
// In your controller
    public function workerPerformanceReport(Request $request)
    {
        $workers = Worker::query();
        if ($request->filled('worker_id')) {
            $workers = $workers->where('worker_ID', $request->worker_id);
        }
        $reportData = [];
        foreach ($workers->get() as $worker) {
            $feedbackCount = $worker->feedbacks()->count();
            $reservationCount = $worker->reservations()->count();
            $averageFeedbackRating = $worker->feedbacks()->avg('rate');

            $reportData[] = [
                'Worker Name' => $worker->full_name,
                'Feedback Count' => $feedbackCount,
                'Reservation Count' => $reservationCount,
                'Average Feedback Rating' => $averageFeedbackRating,
            ];
        }
        return view('admin.reports.worker_performance', compact('reportData'));
    }

    // In your controller
    public function reservationSummaryReport(Request $request)
    {
        $reservationsQ = Reservation::query();
        if ($request->filled('customer_id')) {
            $reservationsQ = $reservationsQ->where('cus_ID', $request->customer_id);
        }
        if ($request->filled('worker_id')) {
            $reservationsQ = $reservationsQ->where('worker_ID', $request->worker_id);
        }
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $reservationsQ = $reservationsQ->whereBetween('res_Date', [$request->date_from, $request->date_to]);
        }
        $reportData = [];
        foreach ($reservationsQ->get() as $reservation) {
            $reportData[] = [
                'res_ID' => $reservation->res_ID,
                'customer' => optional($reservation->customer)->name,
                'worker' => optional($reservation->worker)->full_name,
                'res_Status' => $reservation->res_Status,
                'date' => date('Y-m-d h:i A', strtotime($reservation->res_Date))
            ];
        }
        return view('admin.reports.reservation_summary', compact('reportData'));
    }

    public function workerSalesProfitReport(Request $request) {
        $workers = Worker::query();
        if ($request->filled('worker_id')) {
            $workers = $workers->where('worker_ID', $request->worker_id);
        }
        $reportData = [];
        foreach ($workers->get() as $worker) {
            $reservations = $worker->reservations;
            $totalSales = 0;
            $totalCost = 0;

            foreach ($reservations as $reservation) {
                $totalSales += optional($reservation->bill)->payment_Amount;
                $totalCost += optional($reservation->bill)->payment_Amount * 0.9; // Subtract 10% from the cost
            }

            $totalProfit = $totalSales - $totalCost;

            $reportData[] = [
                'Worker_Name' => $worker->full_name,
                'Total_Sales' => $totalSales,
                'Total_Cost' => $totalCost,
                'Total_Profit' => $totalProfit,
            ];
        }
        // Sort the workers by total profit in descending order (best workers first).
        usort($reportData, function ($a, $b) {
            return $b['Total_Profit'] - $a['Total_Profit'];
        });
        // The first worker in $workerData is the best worker, and the last is the worst.
        $bestWorker = reset($reportData);
        $worstWorker = end($reportData);
        return view('admin.reports.worker_sales_profit', compact('reportData', 'bestWorker', 'worstWorker'));
    }

}
