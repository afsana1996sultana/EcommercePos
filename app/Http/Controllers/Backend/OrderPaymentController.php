<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Address;
use App\Models\OrderDetail;
use App\Models\OrderRefund;
use App\Models\OrderPayment;
use Illuminate\Http\Request;
use App\Models\AccountLedger;
use App\Models\AdvancePayment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class OrderPaymentController extends Controller
{
    
    public function index(Request $request)
    {
        $date = $request->selectdate;
        $orderpays = OrderPayment::query();

        $dateRange = explode(" - ", $date);
        $startDate = date('Y-m-d', strtotime($dateRange[0]));

        if (isset($dateRange[1])) {
            $endDate = date('Y-m-d', strtotime($dateRange[1]));
        } else {
            $endDate = date('Y-m-d');
        }
        if ($request->filled(['selectdate'])) {
            if ($startDate === $endDate) {
                $orderpays->whereDate('created_at', $startDate);
            } else {
                $orderpays->whereBetween('created_at', [$startDate, $endDate]);
            }
        } else {
            $orderpays->orderBy('created_at', 'desc');
        }

        $orderpayments = $orderpays->orderBy('created_at', 'desc')->get();
        return view('backend.sales.payment.index', compact('orderpayments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $users = User::where('role',3)->get();
        $orders = Order::where('due_amount', '!=', 0)->get();
        return view('backend.sales.payment.create', compact('orders'));
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id'           => 'required',
            'amount'            => 'required|numeric',
            'payment_date'      => 'required',
            'receive_amount'    => 'required|numeric',
            'payment_method'    => 'required',
            'transaction_num'   => 'nullable|numeric|digits:11',
            'agent_number'    => 'required',
        ]);

        $invoice_data = OrderPayment::orderBy('id', 'desc')->first();
        if ($invoice_data) {
            $lastId = $invoice_data->id;
            $id = str_pad($lastId + 1, 7, 0, STR_PAD_LEFT);
            $invoice_no = $id;
        } else {
            $invoice_no = "0000001";
        }
        if ($request->ndue_amount < 0) {
            $notification = array(
                'message' => 'Received amount is gather then Due amount',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
        $orderpayment = OrderPayment::create([
            'invoice_no'        => $invoice_no,
            'user_id'           => $request->user_id,
            'amount'            => $request->amount,
            'paid'              => $request->receive_amount,
            'due'               => $request->ndue_amount,
            'transaction_num'   => $request->transaction_num,
            'payment_method'    => $request->payment_method,
            'agent_number'        => $request->agent_number,
            'payment_date'      => $request->payment_date,
            'type'              => 1,
            'order_id'          => $request->order_id,
        ]);
        $order = Order::where('id', $request->order_id)->first();
        // if($request->ndue_amount == 0){
        //     $order->payment_status='paid';
        // }else{
        //     $order->payment_status='unpaid';
        // }
        $order->due_amount = $order->due_amount - $request->receive_amount;
        $order->paid_amount = $order->paid_amount + $request->receive_amount;
        $order->payment_status = $order->payment_status;
        $order->save();
        if ($order->due_amount == 0) {
            $order->payment_status = 'paid';
        } else {
            $order->payment_status = 'unpaid';
        }
        $order->csv_amount = $order->grand_total - $order->paid_amount;
        $order->save();
        if ($order->due_amount == 0) {
            $ledger = AccountLedger::create([
                'account_head_id' => 2,
                'particulars' => 'Invoice No: ' . $order->invoice_no,
                'credit' => $order->grand_total,
                'order_id' => $order->id,
                'type' => 2,
            ]);
            $ledger->balance = get_account_balance() + $order->grand_total;
            $ledger->save();
        }

        //return $order;

        $notification = array(
            'message' => 'Customer Payment Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('payment.index')->with($notification);
    }


    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $payment = OrderPayment::findOrFail($id);
        $users = User::where('role', 3)->get();
        $orders = Order::get();
        return view('backend.sales.payment.edit', compact('payment', 'users', 'orders'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'user_id'           => 'required',
            'amount'            => 'required|numeric',
            'payment_date'      => 'required',
            'receive_amount'    => 'required|numeric',
            'payment_method'    => 'required',
            'discount'          => 'nullable',
        ]);

        if ($request->ndue_amount < 0) {
            $notification = array(
                'message' => 'Received amount is gather then due amount',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }

        $payment = OrderPayment::find($id);
        $payment->update([
            'invoice_no'        => $payment->invoice_no,
            'user_id'           => $request->user_id,
            'amount'            => $request->amount,
            'paid'              => $request->receive_amount,
            'due'               => $request->ndue_amount,
            'discount'          => $request->discount,
            'payment_method'    => $request->payment_method,
            'payment_date'      => $request->payment_date,
            'type'              => 1,
            'order_id'          => $request->order_id,
        ]);
        $order = Order::where('id', $request->order_id)->first();
        if ($request->ndue_amount == 0) {
            $order->payment_status = 'paid';
        } else {
            $order->payment_status = 'unpaid';
        }
        $order->due_amount = $order->due_amount - $request->receive_amount;
        $order->paid_amount = $order->paid_amount + $request->receive_amount;
        $order->payment_status = $order->payment_status;
        $order->save();
        if ($order->due_amount == 0) {
            DB::table('account_ledgers')->where('order_id', $order->id)->delete();
            $ledger = AccountLedger::create([
                'account_head_id' => 2,
                'particulars' => 'Invoice No: ' . $order->invoice_no,
                'credit' => $order->grand_total,
                'order_id' => $order->id,
                'type' => 2,
            ]);
            $ledger->balance = get_account_balance() + $order->grand_total;
            $ledger->save();
        }

        $notification = array(
            'message' => 'Customer Payment Update Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('payment.index')->with($notification);
    }

    public function destroy($id)
    {
        $payment = OrderPayment::findOrFail($id);
        $payment->delete();
        DB::table('account_ledgers')->where('order_id', $payment->order_id)->delete();
        $notification = array(
            'message' => 'Payment Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
    public function getCustomerPayment($invoice_no)
    {
        $order_due = Order::where('invoice_no', $invoice_no)->first();
        $all['total_amount'] = $order_due->grand_total;
        $all['total_due'] = $order_due->due_amount;
        $all['user_id'] = $order_due->user_id;
        $all['order_id'] = $order_due->id;
        return json_encode($all);
    }
    public function getCustomerPhone($user_id)
    {
        $user['phone'] = User::where('id', $user_id)->value('phone');
        return json_encode($user);
    }
    public function getCustomerInvoice($invoice_no)
    {
        $invoice = Order::where('invoice_no', $invoice_no)->get();
        // dd($invoice);
        return json_encode($invoice);
    }

    public function advanced_create()
    {
        $users = User::where('role', 3)->get();
        return view('backend.sales.payment.advancedCreate', compact('users'));
    }
    public function advanced_store(Request $request)
    {

        // Get the latest invoice number
        $latestOrderPayment = OrderPayment::orderBy('id', 'desc')->first();
        $invoice_no = $latestOrderPayment ? str_pad($latestOrderPayment->id + 1, 7, 0, STR_PAD_LEFT) : "0000001";

        $orderPayment = OrderPayment::where('user_id', $request->user_id)
            ->where('advanced_type', 1)
            ->first();

        if ($orderPayment) {
            $this->validate($request, [
                'user_id' => 'required',
                'payment_date' => 'required',
                'receive_amount' => 'required|numeric',
                'payment_method' => 'required',
                'transaction_num' => 'required|unique:order_payments,transaction_num',
            ]);
            // Update existing OrderPayment
            $orderPayment->invoice_no = $invoice_no;
            $orderPayment->payment_method = $request->payment_method;
            $orderPayment->paid += $request->receive_amount;
            $orderPayment->transaction_num = $request->transaction_num;
            $orderPayment->payment_date = $request->payment_date;
            $orderPayment->type = 1;
            $orderPayment->update();

            // Update Account Ledger
            $ledger_balance = get_account_balance() + $orderPayment->paid;
            $ledger = AccountLedger::create([
                'account_head_id' => 2,
                'particulars' => 'Payment ID: ' . $orderPayment->id,
                'credit' => $orderPayment->paid,
                'order_id' => $orderPayment->id,
                'balance' => $ledger_balance,
                'type' => 2,
            ]);
            $ledger->save();
        } else {
            //return $request;
            if (!$request->user_id) {
                $this->validate($request, [
                    'phone' => 'nullable|digits:11',
                ]);
                $user = User::create([
                    'name' => $request->name ?? '',
                    'phone' => $request->phone ?? '',
                    'password' => Hash::make("12345678"),
                    'status' => 1,
                    'role' => 3,
                    'customer_type' => 1,
                    'address' => 'dhaka',
                ]);
                $request->merge(['user_id' => $user->id]);
                Address::create([
                    'user_id' =>  $user->id,
                    'is_default' => 1,
                    'status' => 1,
                    'division_id' => 1,
                    'district_id' => 1,
                    'upazilla_id' => 1,
                    'address' => 'dhaka',
                ]);
            }
            $this->validate($request, [
                'user_id' => 'required',
                'payment_date' => 'required',
                'receive_amount' => 'required|numeric',
                'payment_method' => 'required',
                'transaction_num' => 'required|unique:order_payments,transaction_num',
            ]);
            // Create a new OrderPayment
            $newOrderPayment = new OrderPayment;
            $newOrderPayment->invoice_no = $invoice_no;
            $newOrderPayment->user_id = $request->user_id;
            $newOrderPayment->advanced_type = 1;
            $newOrderPayment->payment_method = $request->payment_method;
            $newOrderPayment->paid = $request->receive_amount;
            $newOrderPayment->transaction_num = $request->transaction_num;
            $newOrderPayment->payment_date = $request->payment_date;
            $newOrderPayment->type = 1;
            $newOrderPayment->save();
            $ledger_balance = get_account_balance() + $newOrderPayment->paid;
            $ledger = AccountLedger::create([
                'account_head_id' => 2,
                'particulars' => 'Payment ID: ' . $newOrderPayment->id,
                'credit' => $newOrderPayment->paid,
                'order_id' => $newOrderPayment->id,
                'balance' => $ledger_balance,
                'type' => 2,
            ]);
            $ledger->save();
        }
        $notification = [
            'message' => 'Customer Advanced Payment Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->route('payment.index')->with($notification);
    }

    public function advanced_edit($id)
    {
        $payment = OrderPayment::findOrFail($id);
        $users = User::where('role', 3)->get();
        $orders = Order::get();
        return view('backend.sales.payment.advancedEdit', compact('payment', 'users', 'orders'));
    }
    public function advanced_update(Request $request, $id)
    {
        $this->validate($request, [
            'payment_date'      => 'required',
            'receive_amount'    => 'required|numeric',
            'payment_method'    => 'required',
            'transaction_num'    => 'required',

        ]);
        $payment = OrderPayment::find($id);
        $payment->update([
            'invoice_no'        => $payment->invoice_no,
            'user_id'           => $payment->user_id,
            'paid'              => $request->receive_amount,
            'payment_method'    => $request->payment_method,
            'payment_date'      => $request->payment_date,
            'transaction_num'   => $payment->transaction_num,
            'advanced_type'     => 1,
            'type'              => 1,
        ]);

        $expense_amount = AccountLedger::where('id', $payment->id)->sum('credit');
        DB::table('account_ledgers')->where('id', $payment->id)->delete();

        $ledger = AccountLedger::create([
            'account_head_id' => 2,
            'particulars' => 'Balance adjustment for payment update',
            'debit' => $expense_amount,
            'type' => 1,
        ]);

        $ledger_balance = get_account_balance() + $payment->paid;

        $ledger = AccountLedger::create([
            'account_head_id' => 2,
            'particulars' => 'Payment ID: ' . $payment->id,
            'credit' => $payment->paid,
            //'payment_id' => $payment->id,
            'order_id' => $payment->id,
            'balance' => $ledger_balance,
            'type' => 2,
        ]);

        $notification = array(
            'message' => 'Customer Advanced Payment Update Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('payment.index')->with($notification);
    }
    public function advanced_destroy($id)
    {
        $payment = OrderPayment::findOrFail($id);

        $expense_amount = AccountLedger::where('order_id', $payment->id)->sum('credit');
        DB::table('account_ledgers')->where('order_id', $payment->id)->delete();

        $ledger = AccountLedger::create([
            'account_head_id' => 1,
            'particulars' => 'Balance adjustment for payment delete',
            'debit' => $expense_amount,
            'type' => 1,
        ]);

        $ledger->balance = get_account_balance() + $expense_amount;
        $ledger->save();

        $payment->delete();

        $notification = array(
            'message' => 'Advanced Payment Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
    public function advancedPayment_searchCustomer(Request $request)
    {
        $request->validate(["search" => "required"]);
        $item = $request->search;
        $users = User::where('name', 'LIKE', '%' . $item . '%')
            ->orWhere('phone', 'LIKE', '%' . $item . '%')
            ->where('status', 1)
            ->latest()->get();
        return view('backend.sales.payment.customerSearch', compact('users'));
    }

    public function advanced_index()
    {
        $startIndex = 0;
        $advance = AdvancePayment::where('advance_amount', '!=', 0)->latest()->paginate(100);
        return view('backend.sales.payment.advance_payment.advancedindex', compact('advance', 'startIndex'));
    }
    public function old_advance_payment_check(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_no' => 'required|numeric',
            'advance_amount' => 'required|numeric',
            'received' => 'required',
            'date' => 'required|date',
            'agent_number' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'error' => $validator->errors()->first()
            ]);
        }
        $requestDate = Carbon::parse($request->date)->toDateString();
        $todayDate = Carbon::today()->toDateString();
        $sameDate = ($todayDate === $requestDate);
        if ($sameDate) {
            $oldAdvance = AdvancePayment::where('transaction_no', $request->transaction_no)
                ->where('advance_amount', $request->advance_amount)
                ->where('received', $request->received)
                ->whereDate('date', $todayDate)
                ->where('agent_number', $request->agent_number)
                ->first();
        } else {
            $oldAdvance = null;
        }

        if ($oldAdvance) {
            $status = 0;
        } else {
            $status = 1;
        }
        return response()->json([
            'status' => $status,
        ]);
    }
    public function advanced_payment_store(Request $request)
    {
        $this->validate($request, [
            'received'      => 'required',
            'advance_amount'      => 'required',
            'transaction_no'    => 'required|numeric|digits:11',
            'date'    => 'required',
            'agent_number'    => 'required',
        ]);
        $duplicate = AdvancePayment::where('transaction_no', $request->transaction_no)->where('advance_amount', $request->advance_amount)->where('received', $request->received)->where('date', $request->date)->where('agent_number', $request->agent_number)->first();

        if ($duplicate) {
            $notification = array(
                'message' => 'Already Add this Payment',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }

        AdvancePayment::create([
            'date'                => $request->date,
            'advance_amount'      => $request->advance_amount,
            'received'            => $request->advance_amount,
            'transaction_no'      => $request->transaction_no,
            'agent_number'        => $request->agent_number,
            'user_id'             => $request->user_id,
        ]);
        $notification = array(
            'message' => 'Advanced Payment Added Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
    public function advanced_payment_edit($id)
    {
        $advance = AdvancePayment::get();
        $item = AdvancePayment::find($id);
        return view('backend.sales.payment.advancedPaymentedit', compact('advance', 'item'));
    }

    public function advanced_payment_updated(Request $request, $id)
    {
        $advanceitem = AdvancePayment::find($id);
        $this->validate($request, [
            'advance_amount'      => 'required',
            'transaction_no'    => 'required|numeric|digits:11',
            'date'    => 'required',
        ]);
        $advanceitem->update([
            'date'               => $request->date,
            'advance_amount'      => $request->advance_amount,
            'received'            => $request->advance_amount,
            'transaction_no'      => $request->transaction_no,
            'agent_number'        => $advanceitem->agent_number,
            'user_id'             => $request->user_id,
        ]);
        $notification = array(
            'message' => 'Advanced Payment Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('advanced.index')->with($notification);
    }
    public function advanced_payment_destroy($id)
    {
        $advanceitem = AdvancePayment::find($id);
        $advanceitem->delete();
        return redirect()->back();
    }
    public function advanced_ledger(Request $request)
    {
        $date = $request->selectdate;
        $agent_number = $request->agent_number;
        $startIndex = 0;
        $advanceQuery = AdvancePayment::where('advance_amount', '=', 0);
        if ($date) {
            $dateRange = explode(" - ", $date);
            $startDate = date('Y-m-d', strtotime($dateRange[0]));
            $endDate = date('Y-m-d', strtotime($dateRange[1]));
            $advanceQuery->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                    ->orWhereDate('created_at', $startDate);
            });
        }
        if ($agent_number) {
            $advanceQuery->where('agent_number', $agent_number);
        }
        $advance = $advanceQuery->orderBy('created_at', 'desc')->paginate(100);
        return view('backend.sales.payment.advance_ledger.advance_ledger', compact('advance', 'date', 'agent_number', 'startIndex'));
    }
    public function advanced_payment_refund($id)
    {
        $advanceitem = AdvancePayment::find($id);
        $refund = new OrderRefund();
        $refund->invoice_no = $advanceitem->invoice_no;
        $refund->refund_amount = $advanceitem->received;
        //$refund->payment_date = $advanceitem->payment_date;
        $refund->payment_method = $advanceitem->payment_method;
        $refund->transaction_id = $advanceitem->transaction_no;
        $refund->agent_number = $advanceitem->agent_number;
        //$refund->refund_type = 1;
        $refund->status == 1;
        $refund->save();
        $advanceitem->delete();

        $notification = array(
            'message' => ' Successfully Refunded',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function advance_ledger_pagination(Request $request)
    {
        if ($request->ajax()) {
            $condition = $request->get('condition');
            $data = $request->search;
            $agent_number = $request->agent_number;
            $dateadd = $request->dateadd;
            $total = 0;
            if ($condition == 'advance_ledger') {
                $advanceQuery = AdvancePayment::where('advance_amount', '=', 0);
                $html = 'backend.sales.payment.advance_ledger.ledger';
            } else {
                $advanceQuery = AdvancePayment::where('advance_amount', '!=', 0);
                $html = 'backend.sales.payment.advance_payment.paymentlist';
            }
            if ($dateadd) {
                $dateRange = explode(" - ", $dateadd);
                $startDate = date('Y-m-d', strtotime($dateRange[0]));
                $endDate = date('Y-m-d', strtotime($dateRange[1]));
                $advanceQuery->where(function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate])
                        ->orWhereDate('created_at', $startDate);
                });
            }
            if ($agent_number) {
                $advanceQuery->where('agent_number', $agent_number);
            }
            // Apply filters
            if ($data) {
                $advanceQuery->where(function ($q) use ($data) {
                    $q->where('transaction_no', 'LIKE', '%' . $data . '%')
                        ->orwhere('order_id', 'LIKE', '%' . $data . '%')
                        ->orwhere('received', 'LIKE', '%' . $data . '%');
                });
            }
            $advance = $advanceQuery->orderBy('created_at', 'desc')->paginate(100);
            $page = $request->input('page', 1);
            $startIndex = ($page - 1) * 100;
            return view($html, compact('advance', 'startIndex', 'total'))->render();
        }
    }

    public function advance_ledger_search(Request $request)
    {
        $data = $request->search;
        $agent_number = $request->agent_number;
        $dateadd = $request->dateadd;
        $total = 0;
        $type = $request->type;
        if ($type == 'advance_ledger') {
            $advanceQuery = AdvancePayment::where('advance_amount', '=', 0);
            $html = 'backend.sales.payment.advance_ledger.ledger';
        } else {
            $advanceQuery = AdvancePayment::where('advance_amount', '!=', 0);
            $html = 'backend.sales.payment.advance_payment.paymentlist';
        }
        if ($dateadd) {
            $dateRange = explode(" - ", $dateadd);
            $startDate = date('Y-m-d', strtotime($dateRange[0]));
            $endDate = date('Y-m-d', strtotime($dateRange[1]));
            $advanceQuery->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                    ->orWhereDate('created_at', $startDate);
            });
        }
        if ($agent_number) {
            $advanceQuery->where('agent_number', $agent_number);
        }
        if ($data) {
            $advanceQuery->where(function ($q) use ($data) {
                $q->where('transaction_no', 'LIKE', '%' . $data . '%')
                    ->orwhere('order_id', 'LIKE', '%' . $data . '%')
                    ->orwhere('received', 'LIKE', '%' . $data . '%');
            });
        }
        $advance = $advanceQuery->orderBy('created_at', 'desc')->paginate(100);
        $page = $request->input('page', 1);
        $startIndex = ($page - 1) * 100;
        return view($html, compact('advance', 'startIndex', 'total'));
    }
}
