<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDetails;
use App\Http\Requests\StoreTarget;
use App\StoreVisitDetail;
use App\StoreVisitTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreVisitController extends Controller
{
    public function index(){
        return view('store_visit/index');
    }

    public function storeTarget(StoreTarget $request){
        $target = StoreVisitTarget::create($request->all() + ['logged_by' => Auth::id()]);

        if($target) return response()->json(['success' => true,'response' => 'storeVisitTarget']);

    }

    public function updateTarget(StoreTarget $request,$id){
        StoreVisitTarget::findOrFail($id)->update($request->all());
        return redirect()->back();
    }

    public function updateDetail(StoreDetails $request,$id){
        StoreVisitDetail::findOrFail($id)->update($request->all());
        return redirect()->back();
    }

    public function deleteTarget($id){
        $target = StoreVisitTarget::destroy($id);

        if($target) return response()
            ->json(['table' => 'targetTable']);

    }

    public function deleteDetails($id){
        $detail = StoreVisitDetail::destroy($id);

        if($detail) return response()
            ->json(['table' => 'detailsTable']);
    }

    public function storeDetails(StoreDetails $request){
        $details = StoreVisitDetail::create($request->all() + ['logged_by' => Auth::id()]);
        if($details) return response()->json(['success' => true,'response' => 'storeVisitDetails']);
    }

    public function editTargetModal($id) {
        $target = StoreVisitTarget::findOrFail($id);
        return view('modal.target_form',compact('target'));
    }

    public function editDetailsModal($id) {
        $detail = StoreVisitDetail::findOrFail($id);
        return view('modal.visit_details_form',compact('detail'));
    }
}
