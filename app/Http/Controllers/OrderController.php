<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Rider;
use App\Models\Shop;
use App\Models\Township;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Order::leftJoin('townships','townships.id','orders.township_id')->leftJoin('riders','riders.id','orders.rider_id')->leftJoin('shops','shops.id','orders.shop_id')->leftJoin('users','users.id','orders.last_updated_by')->select('orders.*','townships.name as township_name','shops.name as shop_name','riders.name as rider_name','users.name as last_updated_by_name');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '
                        <a href="'. route("orders.show", $row->id) .'" class="btn btn-info btn-sm">View</a> 
                        <a href="'. route("orders.edit", $row->id) .'" class="btn btn-light btn-sm">Edit</a> 
                        <form action="'.route("orders.destroy", $row->id) .'" method="post" class="d-inline">
                            <input type="hidden" name="_token" value="'. csrf_token() .'">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="submit" value="Delete" class="btn btn-sm btn-danger"/>
                        </form>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.order.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   
        $shops = Shop::all();
        $riders = Rider::all();
        $townships = Township::all();
        
        return view('admin.order.create',compact('shops', 'riders', 'townships'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'order_code'            => 'required|unique:orders',
            'customer_name'         => 'required',
            'customer_phone_number' => 'required',
            'township_id'           => 'required',
            'rider_id'              => 'required',
            'shop_id'               => 'required',
            'quantity'              => 'required',
            'total_amount'          => 'required',
            'delivery_fees'         => 'required',
            'item_type'             => 'required',
            'type'                  => 'required',
            'collection_method'     => 'required',
        ];

        $customErr = [
            'order_code.required'               => 'Order Code field is required',
            'order_code.unique'                 => 'Order Code already exists',
            'customer_name.required'            => 'Customer Name field is required',
            'customer_phone_number.required'    => 'Customer Phone Number field is required',
            'township_id.required'              => 'Township field is required',
            'rider_id.required'                 => 'Rider field is required',
            'shop_id.required'                  => 'Shop field is required',
            'quantity.required'                 => 'Quantity field is required',
            'total_amount'                      => 'Total Amount field is required',
            'delivery_fees.required'            => 'Delivery Fees is required',
            'item_type.required'                => 'Item Type field is required',
            'type.required'                     => 'Type field is required',
            'collection_method.required'        => 'Collection Method field is required',
            
        ];

        $validator = Validator::make($request->all(), $rules,$customErr);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $order = new Order();
            $order->order_code =  $request->order_code;
            $order->customer_name =  $request->customer_name;
            $order->customer_phone_number =  $request->customer_phone_number;
            $order->township_id =  $request->township_id;
            $order->rider_id =  $request->rider_id ?? null;
            $order->shop_id =  $request->shop_id;
            $order->quantity =  $request->quantity;
            $order->delivery_fees =  $request->delivery_fees;
            $order->total_amount = $request->total_amount;
            $order->markup_delivery_fees =  $request->markup_delivery_fees ?? null;
            $order->remark =  $request->remark ?? null;
            $order->status =  $request->status;
            $order->item_type =  $request->item_type;
            $order->full_address =  $request->full_address ?? null;
            $order->schedule_date =  $request->schedule_date ?? null ;
            $order->type =  $request->type;
            $order->collection_method =  $request->collection_method;
            $order->proof_of_payment =  $request->proof_of_payment ?? null;
            $order->save();
        }
        return redirect()->route('orders.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::with('rider', 'shop', 'township', 'user' )->where('id',$id)->first();
        if(!$order) {
            abort(404);
        }
        return view('admin.order.detail',compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
        $order = Order::where('id', $id)->first();
        $date = new Carbon($order->schedule_date);
        $scheduledate = $date->format('Y-m-d');
        $shops = Shop::all();
        $riders = Rider::all();
        $townships = Township::all();
        if(!$order) {
            abort(404);
        }

        return view('admin.order.edit', compact('order', 'shops', 'riders', 'townships', 'scheduledate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {   
        $rules = [
            'order_code'            => 'required|unique:orders',
            'customer_name'         => 'required',
            'customer_phone_number' => 'required',
            'township_id'           => 'required',
            'rider_id'              => 'required',
            'shop_id'               => 'required',
            'quantity'              => 'required',
            'total_amount'          => 'required',
            'delivery_fees'         => 'required',
            'item_type'             => 'required',
            'type'                  => 'required',
            'collection_method'     => 'required',
        ];

        $customErr = [
            'order_code.required'               => 'Order Code field is required',
            'order_code.unique'                 => 'Order Code already exists',
            'customer_name.required'            => 'Customer Name field is required',
            'customer_phone_number.required'    => 'Customer Phone Number field is required',
            'township_id.required'              => 'Township field is required',
            'rider_id.required'                 => 'Rider field is required',
            'shop_id.required'                  => 'Shop field is required',
            'quantity.required'                 => 'Quantity field is required',
            'total_amount'                      => 'Total Amount field is required',
            'delivery_fees.required'            => 'Delivery Fees is required',
            'item_type.required'                => 'Item Type field is required',
            'type.required'                     => 'Type field is required',
            'collection_method.required'        => 'Collection Method field is required',
            
        ];

        $validator = Validator::make($request->all(), $rules,$customErr);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $order = Order::where('id',$id)->first();
            $order->order_code =  $request->order_code;
            $order->customer_name =  $request->customer_name;
            $order->customer_phone_number =  $request->customer_phone_number;
            $order->township_id =  $request->township_id;
            $order->rider_id =  $request->rider_id ?? null;
            $order->shop_id =  $request->shop_id;
            $order->quantity =  $request->quantity;
            $order->delivery_fees =  $request->delivery_fees;
            $order->total_amount = $request->total_amount;
            $order->markup_delivery_fees =  $request->markup_delivery_fees ?? null;
            $order->remark =  $request->remark ?? null;
            $order->status =  $request->status;
            $order->item_type =  $request->item_type;
            $order->full_address =  $request->full_address ?? null;
            $order->schedule_date =  $request->schedule_date ?? null ;
            $order->type =  $request->type;
            $order->collection_method =  $request->collection_method;
            $order->proof_of_payment =  $request->proof_of_payment ?? null;
            $order->save();
        }
        return redirect()->route('orders.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Order::destroy($id);
        return redirect()->route('orders.index');
    }
}
