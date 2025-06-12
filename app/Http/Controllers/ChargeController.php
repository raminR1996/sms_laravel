<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\UserPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChargeController extends Controller
{
    /**
     * نمایش صفحه شارژ پنل
     */
    public function index()
    {
        $packages = Package::where('is_active', true)->get();
        return view('user.charge', compact('packages'));
    }

    /**
     * نمایش صفحه پرداخت‌های موفق
     */
    public function successfulPayments()
    {
        $payments = Payment::where('user_id', Auth::id())
            ->where('status', 'success')
            ->latest()
            ->get();
        return view('user.payment.successful_payments', compact('payments'));
    }

    /**
     * نمایش صفحه بسته‌های خریداری‌شده
     */
    public function purchasedPackages()
    {
        $userPackages = UserPackage::where('user_id', Auth::id())
            ->with('package')
            ->latest()
            ->get();
        return view('user.package.purchased_packages', compact('userPackages'));
    }

    /**
     * نمایش صفحه تراکنش‌ها
     */
    public function transactions()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->latest()
            ->get();
        return view('user.transaction.transactions', compact('transactions'));
    }
}