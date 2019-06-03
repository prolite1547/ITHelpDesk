<?php

namespace App\Http\Controllers;

use App\CategoryA;
use App\CategoryB;
use App\CategoryC;
use App\Http\Requests\StoreCategoryA;
use App\Http\Requests\StoreCategoryBandC;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
   
    public function musers()
    {
        return view('maintenance.musers');
    }

    public function mcategories()
    {
        return view('maintenance.mcategories');
    }

    public function storeCategoryA(StoreCategoryA $request){
        CategoryA::create(['name' => $request->new_category]);
    }

    public function storeCategoryB(StoreCategoryBandC $request){
        CategoryB::create(['name' => $request->new_category,'catA_id' => $request->category]);
    }

    public function storeCategoryC(StoreCategoryBandC $request){
        CategoryC::create(['name' => $request->new_category,'catB' => $request->category]);
    }

    
}
