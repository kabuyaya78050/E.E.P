<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;

class DonationController extends Controller
{
    public function index()
    {
        $donations = Donation::orderByDesc('created_at')->paginate(20);
        $totals = Donation::where('status', 'paid')
            ->selectRaw('currency, SUM(amount) as total')
            ->groupBy('currency')
            ->pluck('total', 'currency');

        return view('admin.donations.index', compact('donations', 'totals'));
    }
}
