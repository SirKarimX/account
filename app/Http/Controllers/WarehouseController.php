<?php

namespace App\Http\Controllers;

use App\CustomField;
use App\ProductService;
use App\ProductServiceCategory;
use App\ProductServiceUnit;
use App\Tax;
use App\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        if (\Auth::user()->can('manage warehouse')) {
            $warehouses = Warehouse::where('created_by', '=', \Auth::user()->creatorId())->get();
            return view('warehouse.index', compact('warehouses'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function stock_report()

    {if (\Auth::user()->can('stock warehouse')) {
        $productServices = ProductService::where('created_by', '=', \Auth::user()->creatorId())->get();
        $warehouses = Warehouse::orderBy('id')->pluck('name', 'id');
        return view('stockReport.index', compact('productServices', 'warehouses'));
     } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        } }

    public function create()
    {
//        if (\Auth::user()->can('create warehouse')) {
        return view('warehouse.create');
//        } else {
//            return response()->json(['error' => __('Permission denied.')], 401);
//        }
    }

    public function store(Request $request)
    {

//        if (\Auth::user()->can('create warehouse')) {

        $rules = [
            'name' => 'required|unique:warehouses,name',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->route('warehouse.index')->with('error', $messages->first());
        }

        $warehouse = Warehouse::create([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'created_by' => \Auth::user()->creatorId(),
            'created_at' => now(),
        ]);

        return redirect()->route('warehouse.index')->with('success', __('Warehouse successfully created.'));
//        } else {
//            return redirect()->back()->with('error', __('Permission denied.'));
//        }
    }


    public function edit($id)
    {
        $warehouse = Warehouse::find($id);

//        if (\Auth::user()->can('edit product & service')) {
        if ($warehouse->created_by == \Auth::user()->creatorId()) {
            return view('warehouse.edit', compact('warehouse'));
        }
//            } else {
//                return response()->json(['error' => __('Permission denied.')], 401);
//            }
//        } else {
//            return response()->json(['error' => __('Permission denied.')], 401);
//        }
    }


    public function update(Request $request, $id)
    {

        //if (\Auth::user()->can('edit product & service')) {
        $warehouse = Warehouse::find($id);
        if ($warehouse->created_by == \Auth::user()->creatorId()) {

            $rules = [
                'name' => 'required|unique:warehouses,name,' . $id,
            ];

            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->route('warehouse.index')->with('error', $messages->first());
            }

            $warehouse->name = $request->name;
            $warehouse->description = $request->description;
            $warehouse->created_by = \Auth::user()->creatorId();
            $warehouse->save();

            return redirect()->route('warehouse.index')->with('success', __('Warehouse successfully updated.'));
        }
//            } else {
//                return redirect()->back()->with('error', __('Permission denied.'));
//            }
//        } else {
//            return redirect()->back()->with('error', __('Permission denied.'));
//        }
    }


    public function destroy($id)
    {
//        if (\Auth::user()->can('delete product & service')) {
            $warehouse = Warehouse::find($id);
            if ($warehouse->created_by == \Auth::user()->creatorId()) {
                $warehouse->delete();

                return redirect()->route('warehouse.index')->with('success', __('Warehouse successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
//        } else {
//            return redirect()->back()->with('error', __('Permission denied.'));
//        }
    }

}
