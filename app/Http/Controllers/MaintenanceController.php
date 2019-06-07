<?php

namespace App\Http\Controllers;

use App\CategoryA;
use App\CategoryB;
use App\CategoryC;
use App\Email;
use App\EmailGroup;
use App\EmailGroupPivot;
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

    public function getMailsFromGroup($id){
        $emails = EmailGroupPivot::whereEmailGroupId($id)->with('email')->get();
        return view('templates.email_list',['emails' => $emails]);
    }

    public function addMailsFromGroup(Request $request){


        $validatedData = $request->validate([
            'email.*' => 'required',
            'email_group_id' => 'required|numeric',
        ]);

        $email_insert_array = array();

        foreach ($validatedData['email'] as $mail){
            $insert_array = array('email_group_id' => $validatedData['email_group_id']);
            if(is_numeric($mail)){
                $id = (int)$mail;
            }else{
                preg_match('/^\w+([-+.\']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',$mail,$matches);
                if(count($matches) >= 1) {
                    $id = Email::create(['email' => $mail])->id;
                }
            }
            $insert_array['email_id'] = $id;
            array_push($email_insert_array,$insert_array);

        }


        /*check if the inserted emails are already in the to be inserted group*/
        foreach ($email_insert_array as $key => $inserted){
            $email_group = EmailGroup::whereHas('emails',function($query) use($inserted)
                        {
                            $query->where('email_id',$inserted['email_id']);
                        })
                        ->find($validatedData['email_group_id']);
            if(!is_null($email_group)){
                unset($email_insert_array[$key]);
            }
        }

        EmailGroupPivot::insert($email_insert_array);

    }

    public function deleteEmailOnGroup($pivot_id){
        EmailGroupPivot::destroy($pivot_id);
    }
    public function addEmail(Request $request){
        $validatedData = $request->validate([
            'user_id' => 'sometimes|numeric|nullable',
            'email' => 'required|email|unique:emails,email',
        ]);

        Email::create($validatedData);
    }

    public function addEmailGroup(Request $request){
        $validatedData = $request->validate([
            'group_name' => 'required|string|unique:email_groups,group_name|min:2|max:25',
        ]);

        EmailGroup::create($validatedData);
    }
    
}
