<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Invoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DocumentController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function create(Request $request,Invoice $invoice): JsonResponse
    {
        $this->validate($request,[
            'voucher' => ['required','mimes:jpeg,png,jpg,pdf'],
            'delivery_note' => ['required','mimes:jpeg,png,jpg,pdf'],
            'discounted_amount' => ['required','integer','min:1','max:'.$invoice->total],
            'invoice_number' => ['required'],
            'pick_up_date' => ['required','date','after_or_equal:'.$invoice->updated_at->format('Y-m-d')]
        ],[
            'discounted_amount.max' => "The discounted amount should not be greater than the initial amount"
        ]);
        $document = new Document();
        if($request->hasFile('voucher')) {
            $image = $request->file('voucher');
            $filename = auth()->user()->email."_".time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('vouchers'),$filename);
            $path = url('').'/vouchers/' . $filename;

            $document->voucher = $path;
        }
        if($request->hasFile('delivery_note')) {
            $image = $request->file('delivery_note');
            $filename = auth()->user()->email."_".time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('delivery_note'),$filename);
            $path = url('').'/delivery_note/' . $filename;

            $document->delivery_note = $path;
        }
        $invoice->timestamps = false;
        $invoice->discounted_amount = $request->discounted_amount;
        $document->pick_up_date = $request->pick_up_date;
        $document->invoice_number = $request->invoice_number;

        $document->invoice_id = $invoice->id;

        $document->save();
        $invoice->update();

        return response()->json(['success' => true]);
    }
}
