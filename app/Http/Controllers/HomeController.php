<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // إحصائية نسبة تنفيذ الحالات
        $count_all = Invoice::count();
        $count_invoices1 = Invoice::where('value_status', 1)->count();
        $count_invoices2 = Invoice::where('value_status', 2)->count();
        $count_invoices3 = Invoice::where('value_status', 3)->count();

        if ($count_invoices2 == 0) {
            $nspainvoices2 = 0;
        } else {
            $nspainvoices2 = $count_invoices2 / $count_all * 100;
        }

        if ($count_invoices1 == 0) {
            $nspainvoices1 = 0;
        } else {
            $nspainvoices1 = $count_invoices1 / $count_all * 100;
        }

        if ($count_invoices3 == 0) {
            $nspainvoices3 = 0;
        } else {
            $nspainvoices3 = $count_invoices3 / $count_all * 100;
        }

        $chartjs = app()->chartjs
            ->name('barChartTest')
            ->type('bar')
            ->size(['width' => 350, 'height' => 200])
            ->labels(['الفواتير الغير مدفوعة', 'الفواتير المدفوعة', 'الفواتير المدفوعة جزئيًا'])
            ->datasets([
                [
                    "label" => "الفواتير الغير مدفوعة",
                    'backgroundColor' => ['#ec5858'],
                    'data' => [$nspainvoices2]
                ],
                [
                    "label" => "الفواتير المدفوعة",
                    'backgroundColor' => ['#81b214'],
                    'data' => [$nspainvoices1]
                ],
                [
                    "label" => "الفواتير المدفوعة جزئيًا",
                    'backgroundColor' => ['#ff9642'],
                    'data' => [$nspainvoices3]
                ],
            ])
            ->options([]);

        $chartjs_2 = app()->chartjs
            ->name('pieChartTest')
            ->type('pie')
            ->size(['width' => 340, 'height' => 200])
            ->labels(['الفواتير الغير مدفوعة', 'الفواتير المدفوعة', 'الفواتير المدفوعة جزئيًا'])
            ->datasets([
                [
                    'backgroundColor' => ['#ec5858', '#81b214', '#ff9642'],
                    'data' => [$nspainvoices2, $nspainvoices1, $nspainvoices3]
                ]
            ])
            ->options([]);

        return view('home', compact('chartjs', 'chartjs_2'));
    }
}
