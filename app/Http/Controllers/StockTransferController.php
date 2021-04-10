<?php

namespace App\Http\Controllers;

use App\Commons;
use App\ProductService;
use App\StockTransaction;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockTransferController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {if (\Auth::user()->can('transfer warehouse')) {

        $transfers = StockTransaction::where('reference_type', Commons::TRANSFER)
            ->where('referee_id', 0)
            ->select('id', 'dated')
            ->distinct()
            ->get();

        return view('stock-transfer.index', compact('transfers'));
 } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        $product_services = ProductService::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $product_services->prepend('--', '');
        $warehouses = Warehouse::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $warehouses->prepend('--', '');

        return view('stock-transfer.create', compact('warehouses', 'product_services'));

    }


    public function store(Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
                'dated' => 'required',
                'items' => 'required',
                'items.*.quantity' => 'numeric|min:1',
                'items.*.from_warehouse_id' => 'required',
                'items.*.to_warehouse_id' => 'required|different:items.*.from_warehouse_id',
            ], [
                'dated' => 'Transfer date is required',
                'items' => 'Select item in this transfer',
                'items.*.from_warehouse_id.required' => 'Choose From warehouse',
                'items.*.to_warehouse_id.required' => 'Choose To warehouse',
                'items.*.to_warehouse_id.different' => 'Both warehouses must be different',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $product = $request->items[0];

        $existing_stock_in_from_warehouse = StockTransaction::where('item_id', $product['item_id'])
                                ->where('warehouse_id', $product['from_warehouse_id'])
                                ->sum('quantity');

        if(floatval($existing_stock_in_from_warehouse) < floatval($product['quantity'])) {

                return redirect()->back()->with('error', 'Not enough Qty available in From warehouse!');

        }

        DB::beginTransaction();

        //stock transaction
        $from_transfer = StockTransaction::create([
            'dated' => $request->get('dated'),
            'warehouse_id' => $product['from_warehouse_id'],
            'item_id' => $product['item_id'],
            'reference_id' => 0,
            'reference_line_id' => 0,
            'referee_id' => 0,
            'reference_type' => Commons::TRANSFER,
            'quantity' => -$product['quantity'],
            'description' => $product['description'],
            'line_total' => null,
            'created_at' => now()->utc(),
        ]);

        $from_transfer->update([
            'reference_id' => $from_transfer->id,
            'reference_line_id' => $from_transfer->id,
            'referee_line_id' => $from_transfer->id, // for transfer reference
        ]);


        $to_transfer = StockTransaction::create([
            'dated' => $request->get('dated'),
            'warehouse_id' => $product['to_warehouse_id'],
            'item_id' => $product['item_id'],
            'reference_id' => 0,
            'reference_line_id' => 0,
            'referee_id' => $from_transfer->id,
            'reference_type' => Commons::TRANSFER,
            'quantity' => $product['quantity'],
            'description' => $product['description'],
            'line_total' => null,
            'created_at' => now()->utc(),
        ]);

        $to_transfer->update([
            'reference_id' => $to_transfer->id,
            'reference_line_id' => $to_transfer->id,
        ]);

        DB::commit();

        return redirect()->route('stock-transfer.index', $from_transfer->id)
            ->with('success', __('Stock Transfer successfully created.'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $id = \Crypt::decrypt($id);
        $from_transfer = StockTransaction::with('warehouse', 'item')->find($id);
        $to_transfer = StockTransaction::with('warehouse')
                                    ->where('reference_type', Commons::TRANSFER)
                                    ->where('referee_id', $id)
                                    ->first();


        $product_services = ProductService::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $product_services->prepend('--', '');
        $warehouses = Warehouse::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $warehouses->prepend('--', '');

        return view('stock-transfer.edit', compact('from_transfer', 'to_transfer', 'warehouses', 'product_services'));

    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make(
            $request->all(), [
            'dated' => 'required',
            'items' => 'required',
            'items.*.quantity' => 'numeric|min:1',
            'items.*.from_warehouse_id' => 'required',
            'items.*.to_warehouse_id' => 'required|different:items.*.from_warehouse_id',
        ], [
                'dated' => 'Transfer date is required',
                'items' => 'Select item in this transfer',
                'items.*.from_warehouse_id.required' => 'Choose From warehouse',
                'items.*.to_warehouse_id.required' => 'Choose To warehouse',
                'items.*.to_warehouse_id.different' => 'Both warehouses must be different',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        if($from_transfer = StockTransaction::find($id)) {

            $product = $request->items[0];

            $existing_stock_in_from_warehouse = StockTransaction::where('item_id', $product['item_id'])
                ->where('warehouse_id', $product['from_warehouse_id'])
                ->sum('quantity');

            if (floatval($existing_stock_in_from_warehouse) < floatval($product['quantity'])) {
                return redirect()->back()->with('error', 'Not enough Qty available in From warehouse!');
            }

            DB::beginTransaction();

            //stock transaction
            $from_transfer->update([
                'dated' => $request->get('dated'),
                'warehouse_id' => $product['from_warehouse_id'],
                'item_id' => $product['item_id'],
                'quantity' => -$product['quantity'],
                'description' => $product['description'],
                'created_at' => now()->utc(),
            ]);

            $to_transfer = StockTransaction::where('reference_type', Commons::TRANSFER)
                ->where('referee_id', $from_transfer->id)
                ->first();

            $to_transfer->update([
                'dated' => $request->get('dated'),
                'warehouse_id' => $product['to_warehouse_id'],
                'item_id' => $product['item_id'],
                'quantity' => $product['quantity'],
                'description' => $product['description'],
                'created_at' => now()->utc(),
            ]);

            DB::commit();

            return redirect()->route('stock-transfer.index', $from_transfer->id)
                ->with('success', __('Stock Transfer successfully updated.'));
        }
    }

    public function destroy($id)
    {
        if ($from_transfer = StockTransaction::find($id)) {
            DB::beginTransaction();

            $from_transfer->delete();

            $to_transfer = StockTransaction::where('reference_type', Commons::TRANSFER)
                ->where('referee_id', $from_transfer->id)
                ->first();
            $to_transfer->delete();

            DB::commit();

            return redirect()->route('stock-transfer.index')->with('success', __('Stock Transfer successfully deleted.'));
        }
    }
}