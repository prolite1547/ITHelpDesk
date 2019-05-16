<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DevProject;

class DevProjController extends Controller
{
    public function show(){
        $onGoing = DevProject::where('status', '=', 'On-Going')->whereNull('deleted_at')->count();
        $testing = DevProject::where('status','=','Testing')->whereNull('deleted_at')->count();
        $done = DevProject::where('status','=', 'Done')->whereNull('deleted_at')->count();
        $allProj = DevProject::whereNull('deleted_at')->count();

        return view('dev.devprojs', ['onGoing'=> $onGoing , 'testing'=> $testing, 'done'=>$done , 'allProj'=>$allProj ]);
    }

    public function addProject(Request $request){
        DevProject::create([
            'project_name'=>$request->projName,
            'assigned_to'=>$request->assigned,
            'status'=>$request->status,
            'date_start'=>$request->dateStart,
            'date_end'=>$request->dateEnd,
            'md50_status'=>$request->statusmd,
        ]);

        return redirect()->back();
    }

    public function editProject(Request $request){
          $project = DevProject::find($request->projID);
          $project->project_name = $request->projName;
          $project->assigned_to = $request->assigned;
          $project->status = $request->status;
          $project->date_start = $request->dateStart;
          $project->date_end = $request->dateEnd;
          $project->md50_status = $request->statusmd;
          $project->save();
          return redirect()->back();
    }

    public function showEdit($id){
        $project = DevProject::find($id);
        return view('modal.edit_devprojs', ['project'=>$project]);
    }

    public function deleteProject($id){
        date_default_timezone_set("Asia/Manila");
        $currentDate =  date('Y-m-d H:i:s');
        $devProj = DevProject::findorFail($id);
        $devProj->deleted_at = $currentDate;
        $devProj->save();

        // DevProject::findorFail($id)->delete();

        return redirect()->back();
    }


    
}
