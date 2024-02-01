<?php

namespace App\Http\Controllers;

use App\Mail\ApprovedInvoiceMail;
use App\Mail\MailToAMC;
use App\Mail\MailToRequester;
use App\Mail\RejectInvoiceMail;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Proforma;
use App\Models\Vendor;
use App\Models\VendorBranch;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\In;
use Illuminate\Validation\ValidationException;

class InvoiceController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Invoice::query()->where('user_id',auth()->id())
                         ->when(\request('q'),function ($query,$search) {
                             $query->where('item_code',$search);
                             $query->orWhere('item_name',$search);
                         })
                         ->latest("updated_at")
                         ->paginate(\request('perPage'))
                         ->through(fn($invoice) => [
                             'id' => $invoice->id,
                             'shop' => $invoice->branch,
                             'proforma' => $invoice->proformas->map(fn($proforma) => [
                                 'name' => $proforma->name,
                                 'proforma' => $proforma->proforma,
                             ]),
                             'total' => number_format(collect($invoice->items)->reduce(function ($a , $b) {
                                 return $a + ((double)$b->quantity * (double)$b->price);
                             }),2),
                             'branch' => $invoice->vendor_branch?->name,
                             'vendor' => $invoice->vendor?->name,
                             'status' => $invoice->status,
                             "invoice" => $invoice->documents,
                             "date" => $invoice->created_at->format('jS F Y ')
                         ])
        );
    }

    /**
     * @throws ValidationException
     */
    public function create(Request $request): JsonResponse
    {
        $this->validate($request,[
            'branch' => ['required'],
            'proforma' => ['required','array'],
            'proforma.*.name' => ['required'],
            'proforma.*.proforma' => ['required','mimes:jpeg,png,jpg,pdf'],
            'vendor' => ['required'],
            'items' => ['required','array'],
            'items.*.name' => ['required'],
            'items.*.code' => ['required'],
            'items.*.price' => ['required','integer','min:1'],
            'items.*.quantity' => ['required','integer','min:1'],
            'phone_number' => ['required'],
        ]);
        DB::beginTransaction();
        try {

            $vendor = Vendor::query()->where('name',$request->vendor)->first();
            $vendor_branch =  $vendor->vendor_branches->where('name',$request->branch)->first();
            $invoice = new Invoice();
            $invoice->vendor_branch_id = $vendor_branch->id;
            $invoice->status = $request->status;
            $invoice->user_id = auth()->id();
            $invoice->proforma_invoice = auth()->id();
            $invoice->phone_number = $request->phone_number;
            $invoice->vendor_id = $vendor->id;
            $invoice->total = collect($request->items)->reduce(function ($c, $a) {
                return $c + ((double)$a['price'] * (double)$a['quantity']);
            });
            $invoice->save();
            foreach ($request->proforma as $pro) {

                $proforma = $pro['proforma'];
                $filename = auth()->user()->email."_".time().'.'.$proforma->getClientOriginalExtension();
                $proforma->move(public_path('proforma_invoices'),$filename);
                $path = url('').'/proforma_invoices/' . $filename;
                $proforma_invoice = new Proforma();
                $proforma_invoice->proforma = $path;
                $proforma_invoice->name = $pro['name'];
                $proforma_invoice->invoice_id = $invoice->id;

                $proforma_invoice->save();
            }
            foreach ($request->items as $item) {
                $i = new Item();
                $i->name = $item['name'];
                $i->code = $item['code'];
                $i->price = (double)$item['price'];
                $i->quantity = (integer)$item['quantity'];
                $i->invoice_id = $invoice->id;
                $i->user_id = auth()->id();
                $i->save();
            }
            if ($invoice->status == "pending") {
                Mail::to(auth()->user()->email)->send(new MailToRequester($invoice));
//            Mail::to('amc@primeinsuranceghana.com')->send(new MailToAMC($invoice));
            }
            DB::commit();

            return  response()->json(['success' => true]);

        }catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['server' => [
                'item' => [$exception->getMessage()]
            ]],500);
        }
    }

    public function getAllAssets(): JsonResponse
    {
        $from = \request('from') ? Carbon::createFromDate(\request('from')) : now()->startOfYear();
        $to = \request('to') ? Carbon::createFromDate(\request('to')) : now()->addMonths(3)->endOfMonth();
        $s = \request('q');
        return response()->json(Invoice::with('user')
            ->where('status','!=',"draft")
            ->whereHas('user',function ($query) use($s) {
                $query->where('name','like',"%{$s}%");
                $query->orWhere('email','like',"%{$s}%");
            })
            ->when(\request('q'),function ($query,$search) {
                $query->where('id','like',"%{$search}%");
            })
            ->when(\request('status'),function ($query,$status) {
                if ($status == "ch") {
                    $query->where('status','!=','pending');
                }else{
                    $query->where('status',$status);
                }

            })
            ->when(\request('with_invoice'),function ($query,$with_invoice) {
                if ($with_invoice == "with invoice") {
                    $query->whereHas('documents');
                }else{
                    $query->whereDoesntHave('documents');
                }
            })
            ->when(\request('q'),function ($query,$search) {
                $query->where('item_code',$search);
                $query->orWhere('item_name',$search);
            })
            ->latest("updated_at")
            ->whereBetween('created_at',[$from,$to])
            ->paginate(\request('perPage'))
            ->through(fn($invoice) => [
                'id' => $invoice->id,
                'discounted_amount' => $invoice->discounted_amount ,
                'shop' => $invoice->branch,
                'user' => $invoice->user,
                'proforma' => $invoice->proformas->map(fn($proforma) => [
                    'name' => $proforma->name,
                    'proforma' => $proforma->proforma,
                ]),
                'item_description' => $invoice->branch,
                'branch' => $invoice->vendor_branch?->name,
                'total' => number_format(collect($invoice->items)->reduce(function ($a , $b) {
                    return $a + ((double)$b->quantity * (double)$b->price);
                }),2),
                "invoice" => $invoice->documents,
                "status" => $invoice->status,
                "date" => $invoice->created_at->format('jS F Y ')
            ])
        );
    }
    public function show(Invoice $invoice): JsonResponse
    {
        return response()->json([
            'status' => $invoice->status,
            'id' => $invoice->id,
            'comments' => $invoice->comments,
            'invoice' => $invoice->invoice,
            'total' => number_format(collect($invoice->items)->reduce(function ($a , $b) {
                return $a + ((double)$b->quantity * (double)$b->price);
            }),2),
            'items' => $invoice->items,
            'user' => $invoice->user->name ,
            'phone_number' => $invoice->phone_number ,
            'date' => $invoice->updated_at->format('d/m/Y'),
            "vendor" => $invoice->vendor?->name,
            "branch" => $invoice->vendor_branch?->name,
            "proforma" => $invoice->proformas,
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function update(Request $request, Invoice $invoice): JsonResponse|\Illuminate\Mail\SentMessage|null
    {
        $this->validate($request,[
            'status' => ['required']
        ]);

        if ($invoice->status !== 'pending') {
            return response()->json(['errors' => [
                'status' => ["The status of the invoice has already being set"]
            ]],422);
        }
        $invoice->status = $request->status;
        $invoice->comments = $request->comments;
        $invoice->timestamps = false;
        $invoice->update();
        if ($request->status == "rejected") {

            Mail::to($invoice->user->email)->send(new RejectInvoiceMail($invoice));
            return response()->json(['success']);
        }

        Mail::to($invoice->user->email)->send(new ApprovedInvoiceMail($invoice));
        return response()->json(['success']);
    }

    public function downloadInvoice(): JsonResponse
    {
        $from = \request('from') ? Carbon::createFromDate(\request('from')) : now()->startOfMonth();
        $to = \request('to') ? Carbon::createFromDate(\request('to')) : now()->addMonths(3)->endOfMonth();
        $s = \request('q');
        return response()->json(Invoice::with('user')
            ->whereHas('user',function ($query) use($s) {
                $query->where('name','like',"%{$s}%");
                $query->orWhere('email','like',"%{$s}%");
            })
            ->when(\request('q'),function ($query,$search) {
                $query->where('id','like',"%{$search}%");
            })
            ->when(\request('status'),function ($query,$status) {
                if ($status == "ch") {
                    $query->where('status','!=','pending');
                }else{
                    $query->where('status',$status);
                }

            })
            ->when(\request('with_invoice'),function ($query,$with_invoice) {
                if ($with_invoice == "with invoice") {
                    $query->whereHas('documents');
                }else{
                    $query->whereDoesntHave('documents');
                }
            })
            ->when(\request('q'),function ($query,$search) {
                $query->where('item_code',$search);
                $query->orWhere('item_name',$search);
            })
            ->latest()
            ->whereBetween('created_at',[$from,$to])
            ->get()
            ->map(fn($invoice) => [
                'REQUEST DATE' => $invoice->updated_at->format('d-m-Y'),
                'STAFF NAME' => $invoice->user->name,
                'ITEM CODE' => collect($invoice->items)->implode("code",","),
                'DESCRIPTION' => collect($invoice->items)->implode("name",","),
                'PROFORMA TOTAL' => number_format(collect($invoice->items)->reduce(function ($a , $b) {
                    return $a + ((double)$b->quantity * (double)$b->price);
                }),2),
                "STATUS" => $invoice->status,
                "PICK-UP DATE" => $invoice->documents->first()?->pick_up_date,
                'DISCOUNTED_AMOUNTED' => $invoice->discounted_amount,
                "INVOICE NO." => $invoice->documents->first()?->invoice_number,
            ])
        );
    }

    /**
     * @throws ValidationException
     */
    public function updateData(Request $request, Invoice $invoice): JsonResponse
    {
//        foreach ($request->proforma as $pro) {
//            $proforma = $pro['proforma'];
//
//            dd(gettype($proforma));
//        }
        $this->validate($request,[
            'branch' => ['required'],
            'proforma' => ['required','array'],
            'proforma.*.name' => ['required'],
            'proforma.*.proforma' => ['required'],
            'vendor' => ['required'],
            'items' => ['required','array'],
            'items.*.name' => ['required'],
            'items.*.code' => ['required'],
            'items.*.price' => ['required','integer','min:1'],
            'items.*.quantity' => ['required','integer','min:1'],
            'phone_number' => ['required'],
        ]);
        DB::beginTransaction();
        try {

            $vendor = Vendor::query()->where('name',$request->vendor)->first();
            $vendor_branch =  $vendor->vendor_branches->where('name',$request->branch)->first();
            $invoice->vendor_branch_id = $vendor_branch->id;
            $invoice->status = $request->status;
            $invoice->phone_number = $request->phone_number;
            $invoice->vendor_id = $vendor->id;
            $invoice->total = collect($request->items)->reduce(function ($c, $a) {
                return $c + ((double)$a['price'] * (double)$a['quantity']);
            });
            $invoice->update();

            foreach ($invoice->proformas as $proforma) {
                $proforma->delete();
            }
            foreach ($request->proforma as $pro) {
                $proforma = $pro['proforma'];
                if (gettype($proforma) == "string") {

                    $proforma_invoice = new Proforma();
                    $proforma_invoice->proforma = $proforma;
                    $proforma_invoice->name = $pro['name'];
                    $proforma_invoice->invoice_id = $invoice->id;
                    $proforma_invoice->save();

                }else {
                    $filename = auth()->user()->email."_".time().'.'.$proforma->getClientOriginalExtension();
                    $proforma->move(public_path('proforma_invoices'),$filename);
                    $path = url('').'/proforma_invoices/' . $filename;
                    $proforma_invoice = new Proforma();
                    $proforma_invoice->proforma = $path;
                    $proforma_invoice->name = $pro['name'];
                    $proforma_invoice->invoice_id = $invoice->id;

                    $proforma_invoice->save();
                }
            }

            foreach ($invoice->items as $item) {
                $item->delete();
            }
            foreach ($request->items as $item) {
                $i = new Item();
                $i->name = $item['name'];
                $i->code = $item['code'];
                $i->price = (double)$item['price'];
                $i->quantity = (integer)$item['quantity'];
                $i->invoice_id = $invoice->id;
                $i->user_id = auth()->id();
                $i->save();
            }

            if ($invoice->status == "pending") {
                Mail::to(auth()->user()->email)->send(new MailToRequester($invoice));
//            Mail::to('amc@primeinsuranceghana.com')->send(new MailToAMC($invoice));
            }

            DB::commit();
            return  response()->json($invoice);

        }catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['server' => [
                'item' => [$exception->getMessage()]
            ]],500);
        }
    }
}
