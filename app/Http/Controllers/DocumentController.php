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
            'voucher' => ['required'],
            'delivery_note' => ['required'],
            'discounted_amount' => ['required','integer','min:1','max:'.$invoice->total]
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

        $invoice->discounted_amount = $request->discounted_amount;

        $document->invoice_id = $invoice->id;

        $document->save();
        $invoice->update();

        return response()->json(['success' => true]);
    }
}
