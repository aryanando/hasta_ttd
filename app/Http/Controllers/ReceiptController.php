<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receipt;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    public function index()
    {
        $receipts = Receipt::orderBy('created_at', 'desc')->get();
        return view('receipts.index', compact('receipts'));
    }

    public function create()
    {
        return view('receipts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'amount_in_words' => 'required|string',
            'recipient' => 'required|string|max:255',
        ]);

        Receipt::create([
            'amount' => $request->amount,
            'amount_in_words' => $request->amount_in_words,
            'recipient' => $request->recipient,
            'payment_purpose' => $request->payment_purpose
        ]);

        return redirect()->route('kwitansi-dokter-dashboard')->with('success', 'Receipt saved successfully!');
    }

    public function show(Receipt $receipt)
    {
        return view('receipts.show', compact('receipt'));
    }

    public function destroy(Receipt $receipt)
    {
        $receipt->delete();
        return redirect()->route('kwitansi-dokter-dashboard')->with('success', 'Receipt deleted successfully!');
    }

    public function showPublic($public_code)
    {
        $receipt = Receipt::where('public_code', $public_code)->firstOrFail();
        return view('ttd_kwitansi_dokter', compact('receipt'));
    }

    public function saveSignature(Request $request, $public_code)
    {
        $receipt = Receipt::where('public_code', $public_code)->firstOrFail();

        $request->validate([
            'signature' => 'required|string',
        ]);

        $receipt->update([
            'signature' => $request->signature,
        ]);

        return redirect()->route('receipts.public', $public_code)->with('success', 'Signature saved successfully!');
    }

    // Generate PDF for Receipt
    public function generatePdf($public_code)
    {
        $receipt = Receipt::where('public_code', $public_code)->firstOrFail();

        $pdf = Pdf::loadView('receipts.pdf', compact('receipt'))
                    ->setPaper('a4', 'landscape'); // Set A4 landscape

        return $pdf->download('receipt_' . $receipt->public_code . '.pdf');
    }
}
