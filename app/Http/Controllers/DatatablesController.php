<?php

namespace App\Http\Controllers;

use App\Status;
use App\Ticket;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function PHPSTORM_META\type;
use Yajra\DataTables\Facades\DataTables;
use App\User;
use App\Category;
use App\Store;
use App\SystemDataCorrection;
use App\ManualDataCorrection;




class DatatablesController extends Controller
{


    public function ajax()
    {
        return Datatables::of(User::query())->make(true);
    }

    public function tickets($status)
    {
        if($status === 'pos'){
            /*catB id's of pos categories*/
            $pos_categories_array = DB::table('category_a as a')->join('category_b as b','a.id','b.catA_id')->where('a.id','=',1)->pluck('b.id')->toArray();
            /*query tickets that are pos related*/
            $query = DB::table('v_tickets as vt')->select('vt.id','vt.category','vt.ticket_group','vt.subject','vt.details','vt.status_name','vt.assigne','vt.store_name','logged_by','vt.priority','vt.created_at')->whereIn('vt.catB',$pos_categories_array);
        }else{
            $statuses = Status::whereNotIn('name', ['fixed','closed'])->pluck('name')->toArray();
            $query = DB::table('v_tickets as vt')
                ->select(
                    'vt.id', 'priority_name', 'status_id','status_name', 'expiration', 'vt.created_at',
                    'subject', 'details', 'category',
                    'assigned_user',
                    'store_name',
                    'logger',
                    'ticket_group_name',
                    'times_extended'
                )
                ->when(in_array(strtolower($status), array_map('strtolower', $statuses), true), function ($query) use ($status) {
                    $get_status = Status::where('name', $status)->firstOrFail();
                    return $query->whereStatusId($get_status->id);
                })
                ->when($status === 'my', function ($query) {
                    return $query->whereAssigneeId(Auth::user()->id)->where('status_id', '!=', 3);
                })
                ->when($status === 'fixed', function ($query) {

                    $group = Auth::user()->group;

                    return $query->whereStatusId(4)
                        ->when($group, function ($query, $group) {
                            return $query->whereGroup($group);
                        })
                        ->leftJoinSub(DB::table('v_latest_fixes'), 'fixed_details', function ($join) {
                            $join->on('vt.id', '=', 'fixed_details.ticket_id');
                        })->leftJoin('users as fixer', 'fixed_details.fixed_by', 'fixer.id')
                        ->addSelect(DB::raw('CONCAT(fixer.fName," ",fixer.lName) as fixed_by'), 'fix_date');

                })
                ->when($status === 'closed',function ($query){
                    return $query->join('v_resolves','vt.id','v_resolves.ticket_id')
                        ->addSelect('resolver','resolved_date');
                });
        }
        $datatablesJSON = DataTables::of($query);
        return $datatablesJSON->make(true);
    }

    // public function sdc(){
    //    $query = DB::table('system_data_corrections')
    //    ->join('vt', 'system_data_corrections.ticket_no', 'tickets.id')
    //    ->leftjoin('incidents', 'tickets.incident_id','incidents.id')
    //    ->selectRaw('system_data_corrections.id,system_data_corrections.sdc_no ,tickets.id as ticket_id ,incidents.subject, system_data_corrections.requestor_name, system_data_corrections.dept_supervisor ,system_data_corrections.department, system_data_corrections.position, system_data_corrections.date_submitted, system_data_corrections.posted');
    //    $datatablesJSON = DataTables::of($query);
    //    return $datatablesJSON->make(true);
    // }

    public function sdc()
    {
        $query = DB::table('system_data_corrections')
        ->join('tickets', 'system_data_corrections.ticket_no', 'tickets.id')
        ->leftjoin('incidents', 'tickets.incident_id','incidents.id')
        ->selectRaw('system_data_corrections.id,system_data_corrections.sdc_no ,tickets.id as ticket_id ,incidents.subject, system_data_corrections.requestor_name, system_data_corrections.dept_supervisor ,system_data_corrections.department, system_data_corrections.position, system_data_corrections.date_submitted, system_data_corrections.posted ');
        $query = $query->orderBy('system_data_corrections.created_at', 'desc');
        $datatablesJSON = DataTables::of($query);
        return $datatablesJSON->make(true);
    }

    public function system($status)
    {


        $query = DB::table('system_data_corrections')
        ->join('tickets', 'system_data_corrections.ticket_no', 'tickets.id')
        ->leftjoin('incidents', 'tickets.incident_id','incidents.id')
        ->selectRaw('system_data_corrections.id,system_data_corrections.sdc_no ,tickets.id as ticket_id ,incidents.subject, system_data_corrections.requestor_name, system_data_corrections.dept_supervisor ,system_data_corrections.department, system_data_corrections.position, system_data_corrections.date_submitted, system_data_corrections.forward_status, system_data_corrections.status');
        
        switch($status){
            case "fordeployment":
                $query = $query->where('system_data_corrections.status', '3');
            break;
            case "ty1":
                $query = $query->where('system_data_corrections.forward_status', '1')->where('system_data_corrections.status', '1');
            break;
            case "ty2":
                 $query = $query->where('system_data_corrections.forward_status', '2')->where('system_data_corrections.status', '1');
            break;
            case "govcomp":
                 $query =  $query->where('system_data_corrections.forward_status', '3')->where('system_data_corrections.status', '1');
            break;
            case "finalapp":
                 $query =  $query->where('system_data_corrections.forward_status', '4')->where('system_data_corrections.status', '1');
            break;
            case "draft":
                 $query = $query->where('system_data_corrections.status', '0');
            break;
            case "done":
                 $query = $query->where('system_data_corrections.status', '4');
            break;
            case "rejected":
                 $query = $query->where('system_data_corrections.status', '2');
            break;

        }
        $query = $query->orderBy('system_data_corrections.created_at', 'desc');
        $datatablesJSON = DataTables::of($query);
        return $datatablesJSON->make(true);
    }


    public function mdc()
    {
        $query = DB::table('manual_data_corrections')
            ->join('tickets', 'manual_data_corrections.ticket_no', 'tickets.id')
            ->leftjoin('incidents', 'tickets.incident_id', 'incidents.id')
            ->selectRaw('manual_data_corrections.id,manual_data_corrections.mdc_no ,tickets.id as ticket_id ,incidents.subject, manual_data_corrections.requestor_name ,manual_data_corrections.department, manual_data_corrections.position, manual_data_corrections.date_submitted, manual_data_corrections.posted');
        $query = $query->orderBy('manual_data_corrections.created_at', 'desc');
        $datatablesJSON = DataTables::of($query);
        return $datatablesJSON->make(true);
    }

    public function treasury($status)
    {
        $query = DB::table('system_data_corrections')
            ->join('tickets', 'system_data_corrections.ticket_no', 'tickets.id')
            ->leftjoin('incidents', 'tickets.incident_id', 'incidents.id')
            ->selectRaw('system_data_corrections.id,
        system_data_corrections.sdc_no,
        tickets.id as ticket_id,
        incidents.subject, 
        system_data_corrections.requestor_name,
        system_data_corrections.dept_supervisor,
        system_data_corrections.department,
        system_data_corrections.position,
        system_data_corrections.date_submitted,
        system_data_corrections.status,
        system_data_corrections.forward_status');
        
     if(Auth::user()->role_id == 5){
        if($status != "all"){
            if($status == "pending"){
                 $query = $query->where('system_data_corrections.status', '1')->where('system_data_corrections.forward_status', '1');
            }elseif($status == "done"){
                 $query = $query->whereIn('system_data_corrections.status', array('1','3','4') )->whereIn('system_data_corrections.forward_status', array('2','3','4','5'));
            }
         }else{
             $query = $query->whereIn('system_data_corrections.status',  array('1','3','4'))->whereIn('system_data_corrections.forward_status', array('1','2','3','4','5'));
         }
     }else{
        if($status != "all"){
            if($status == "pending"){
                 $query = $query->where('system_data_corrections.status', '1')->where('system_data_corrections.forward_status', '2');
            }elseif($status == "done"){
                 $query = $query->whereIn('system_data_corrections.status', array('1','3','4'))->whereIn('system_data_corrections.forward_status', array('3','4','5'));
            }
         }else{
             $query = $query->whereIn('system_data_corrections.status', array('1','3','4'))->whereIn('system_data_corrections.forward_status', array('2','3','4','5'));
         }
     }  
        $query = $query->orderBy('system_data_corrections.created_at', 'desc');
        $datatablesJSON = DataTables::of($query);
        return $datatablesJSON->make(true);
    }

    public function govcomp($status)
    {
        $query = DB::table('system_data_corrections')
            ->join('tickets', 'system_data_corrections.ticket_no', 'tickets.id')
            ->leftjoin('incidents', 'tickets.incident_id', 'incidents.id')
            ->selectRaw('system_data_corrections.id,
        system_data_corrections.sdc_no,
        tickets.id as ticket_id,
        incidents.subject, 
        system_data_corrections.requestor_name,
        system_data_corrections.dept_supervisor,
        system_data_corrections.department,
        system_data_corrections.position,
        system_data_corrections.date_submitted,
        system_data_corrections.forward_status,
        system_data_corrections.status');
        
        if($status != "all"){
           if($status == "pending"){
                $query = $query->where('system_data_corrections.forward_status', '3')->where('system_data_corrections.status', '1');
           }elseif($status == "done"){
                $query = $query->whereIn('system_data_corrections.forward_status',  array('4','5'))->whereIn('system_data_corrections.status', array('1','3','4'));
           }
        }else{
            $query = $query->whereIn('system_data_corrections.forward_status', array('3','4','5'))->whereIn('system_data_corrections.status', array('1','3','4'));
        }
        $query = $query->orderBy('system_data_corrections.created_at', 'desc');
        $datatablesJSON = DataTables::of($query);
        return $datatablesJSON->make(true);
    }


    public function approver($status)
    {
        $query = DB::table('system_data_corrections')
            ->join('tickets', 'system_data_corrections.ticket_no', 'tickets.id')
            ->leftjoin('incidents', 'tickets.incident_id', 'incidents.id')
            ->selectRaw('system_data_corrections.id,
        system_data_corrections.sdc_no,
        tickets.id as ticket_id,
        incidents.subject, 
        system_data_corrections.requestor_name,
        system_data_corrections.dept_supervisor,
        system_data_corrections.department,
        system_data_corrections.position,
        system_data_corrections.date_submitted,
        system_data_corrections.forward_status,
        system_data_corrections.status');
        
        if($status != "all"){
           if($status == "pending"){
                $query = $query->where('system_data_corrections.forward_status', '4')->where('system_data_corrections.status', '1');
           }elseif($status == "done"){
                $query = $query->where('system_data_corrections.forward_status', '5')->whereIn('system_data_corrections.status', array('1','3','4'));
           }
        }else{
            $query = $query->whereIn('system_data_corrections.forward_status', array('4','5'))->whereIn('system_data_corrections.status', array('1','3','4'));
        }
        $query = $query->orderBy('system_data_corrections.created_at', 'desc');
        $datatablesJSON = DataTables::of($query);
        return $datatablesJSON->make(true);
    }

}
