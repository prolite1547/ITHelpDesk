<?php

namespace App\Http\Controllers;

use App\CategoryA;
use App\CategoryB;
use App\Department;
use App\Expiration;
use App\Http\Resources\CallerCollection;
use App\Http\Resources\CategoyAResource;
use App\Http\Resources\CategoyBResource;
use App\Http\Resources\ContactCollection;
use App\Http\Resources\DepartmentCollection;
use App\Http\Resources\ExpirationCollection;
use App\Http\Resources\PositionCollection;
use App\Http\Resources\StoreCollection;
use App\Http\Resources\UserResource;
use App\Position;
use App\Store;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SelectController extends Controller
{

    public function branch(Request $request){

        if($request->query('q')){
            return new StoreCollection(Store::where('store_name','LIKE', "%{$request->query('q')}%")->get(['id' ,'store_name as text']));
        }else{
            return new StoreCollection(Store::all(['id' ,'store_name as text']));
        }

    }

    public function caller(Request $request){

        if($request->query('q')){
            return DB::table('callers')->selectRaw('CONCAT(fName," ",mName," ",lName) as text,id')
                ->where(function($query) use ($request) {
                    $query->where('fName', 'LIKE', "%{$request->query('q')}%")
                        ->orWhere('mName', 'LIKE', "%{$request->query('q')}%")
                        ->orWhere('lName', 'LIKE', "%{$request->query('q')}%");
                })->get();
        }else{
            return new CallerCollection(Store::with('callers')->get());
        }


    }

    public function contact(Request $request){

        if($request->query('q')){
            return new ContactCollection(Store::with('contactNumbers')->whereHas('contactNumbers',function ($query) use($request){
                $query->where('number', 'like', "%{$request->query('q')}%");
            })->get());
        }else{
            return new ContactCollection(Store::with('contactNumbers')->get());
        }


    }

    public function position(Request $request){

        if($request->query('q')){
            return new PositionCollection(Position::where('position', 'like', "%{$request->query('q')}%")->get(['id','position as text']));
        }else{
            return new PositionCollection(Position::all(['id','position as text']));
        }


    }

    public function department(Request $request){

        if($request->query('q')){
            return new DepartmentCollection(Department::where('department', 'like', "%{$request->query('q')}%")->get(['id','department as text']));
        }else{
            return new DepartmentCollection(Department::all(['id','department as text']));
        }
    }

    public function expiration(Request $request){
        return new ExpirationCollection(Expiration::all(['id','expiration as text']));
    }

    public function users(Request $request){

        if($request->query('q')){
            return UserResource::collection(User::whereRaw('CONCAT_WS(" ",fName,mName,lName) LIKE "%'.$request->q.'%"')->get());
        }else{
            return UserResource::collection(User::where('userable_type','<>','App\TempUser')->orderBy('fName')->get());
        }
    }

    public function techUsers(Request $request){

        if($request->query('q')){
            return UserResource::collection(User::whereRaw('CONCAT_WS(" ",fName,mName,lName) LIKE "%'.$request->q.'%"')->get());
        }else{
            return UserResource::collection(
                DB::table('users as u')
                    ->join('positions as p','u.position_id','=','p.id')
                    ->where('p.position','LIKE',"%Technical%")
                    ->select('u.id',DB::raw("concat_ws(' ',fName,mName,lName) as full_name"))
                    ->get()
            );
        }
    }

    public function categoryA(Request $request){

        if($request->query('q')){
            return CategoyAResource::collection(CategoryA::where('name','LIKE',"%{$request->q}%")->get(['id','name as text']));
        }else{
            return CategoyAResource::collection(CategoryA::all('id','name as text'));
        }
    }

    public function categoryB(Request $request){

        if($request->query('q')){
            return CategoyBResource::collection(CategoryB::where('name','LIKE',"%{$request->q}%")->get(['id','name as text']));
        }else{
            return CategoyBResource::collection(CategoryB::all('id','name as text'));
        }
    }

}
