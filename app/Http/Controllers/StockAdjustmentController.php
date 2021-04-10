<?php

namespace App\Http\Controllers;

use App\Commons;
use App\Customer;
use App\CustomField;
use App\Invoice;
use App\InvoiceProduct;
use App\ProductService;
use App\ProductServiceCategory;
use App\StockTransaction;
use App\Utility;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockAdjustmentController extends Controller
{

    public function index()
    {if (\Auth::user()->can('adj warehouse')) {

        $query = DB::table('stock_transactions')
            ->join('product_services', 'product_services.id', '=', 'stock_transactions.item_id')
            ->join('warehouses', 'warehouses.id', '=', 'stock_transactions.warehouse_id')
            ->where('stock_transactions.reference_type', Commons::ADJUST)
            ->select([
                'warehouses.name as warehouse',
                'product_services.name as product',
                'stock_transactions.*',
            ]);

        $adjustments = $query->get();

        /*if (!empty($request->status)) {
            $query->where('status', '=', $request->status);
        }
        $invoices = $query->get();*/

        return view('stock-adjustment.index',
            compact('adjustments'));
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

        return view('stock-adjustment.create', compact('warehouses', 'product_services'));
    }

    public function store(Request $request)
    {

        $validator = \Validator::make(
            $request->all(), [
                'dated' => 'required',
                'items' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        DB::beginTransaction();
        $product = $request->items[0];
        //stock transaction
        $adjustment = StockTransaction::create([
            'dated' => $request->get('dated'),
            'warehouse_id' => $product['warehouse_id'],
            'item_id' => $product['item'],
            'reference_id' => 0,
            'reference_line_id' => 0,
            'referee_id' => null,
            'reference_type' => Commons::ADJUST,
            'quantity' => $product['quantity'],
            'description' => $product['description'],
            'line_total' => null,
            'created_at' => now()->utc(),
        ]);

        $adjustment->update([
            'reference_id' => $adjustment->id,
            'reference_line_id' => $adjustment->id,
        ]);

        DB::commit();

        return redirect()->route('stock-adjustment.index', $adjustment->id)->with('success', __('Adjustment successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $id = \Crypt::decrypt($id);
        $adjustment = StockTransaction::with('item')->find($id);
        $product_services = ProductService::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $product_services->prepend('--', '');
        $warehouses = Warehouse::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $warehouses->prepend('--', '');

        return view('stock-adjustment.edit', compact('adjustment','warehouses', 'product_services'));
    }

    public function update(Request $request, $id)
    {

        $validator = \Validator::make(
            $request->all(), [
                'dated' => 'required',
                'items' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        if($adjustment = StockTransaction::find($id)) {

            DB::beginTransaction();
            $product = $request->items[0];
            //stock transaction
            $adjustment->update([
                'dated' => $request->get('dated'),
                'warehouse_id' => $product['warehouse_id'],
                'item_id' => $product['item_id'],
                'quantity' => $product['quantity'],
                'description' => $product['description'],
            ]);

            DB::commit();

            return redirect()->route('stock-adjustment.index', $adjustment->id)->with('success', __('Adjustment successfully updated.'));
        }
    }

    public function destroy($id)
    {
        if ($adjustment = StockTransaction::find($id)) {
            DB::beginTransaction();

            $adjustment->delete();

            DB::commit();

            return redirect()->route('stock-adjustment.index')->with('success', __('Adjustment successfully deleted.'));
        }
    }
}
