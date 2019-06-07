<?php

namespace App\Http\Controllers;

use App\CategoryA;
use Illuminate\Http\Request;

class CategoryAController extends Controller
{
    //
    public function subBCategories($id){
        $sub_categories = \App\CategoryB::where('catA_id',$id)->get(['id','name']);

        return ($sub_categories) ?  response()->json($sub_categories) : response('Failed to Get Sub Categories',404);

    }
}
