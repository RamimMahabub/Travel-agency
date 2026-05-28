<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Models\Property;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    public function index()
    {
        $properties = Property::approved()->with('owner')->get();
        
        $payoutData = [];
        foreach ($properties as $property) {
            // MVP Mock data for pending payouts
            $payoutData[] = (object) [
                'property' => $property,
                'pending_amount' => rand(500, 5000), // MOCK amount
                'last_payout' => Payout::where('property_id', $property->id)->latest('processed_at')->first()
            ];
        }

        return view('admin.payouts.index', compact('payoutData'));
    }

    public function process(Request $request, Property $property)
    {
        $request->validate(['amount' => 'required|numeric|min:1']);

        Payout::create([
            'property_id' => $property->id,
            'amount' => $request->amount,
            'status' => 'processed',
            'period_start' => now()->subMonth()->startOfMonth(),
            'period_end' => now()->subMonth()->endOfMonth(),
            'processed_at' => now(),
            'reference' => 'PAY-' . strtoupper(\Illuminate\Support\Str::random(6))
        ]);

        return back()->with('success', "Payout of $" . number_format($request->amount, 2) . " processed for {$property->name}.");
    }
}
