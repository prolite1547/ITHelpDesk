<?php

namespace App\Http\Controllers;

use App\Status;
use App\StoreVisitDetail;
use App\StoreVisitTarget;
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
use App\DevProject;
use App\MasterDataIssue;



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
            $query = DB::table('v_tickets as vt')->select('vt.id','vt.catB_name','vt.category','vt.ticket_group_name','vt.subject','vt.details','vt.status_name','vt.assigned_user','vt.store_name','logger','vt.priority_name','vt.created_at')->whereIn('vt.catB',$pos_categories_array);
        }else{
            $statuses = Status::whereNotIn('name', ['fixed','closed'])->pluck('name')->toArray();
            $group = Auth::user()->group;
            $query = DB::table('v_tickets')
                ->select(
                    'v_tickets.id', 'v_tickets.priority_name', 'v_tickets.status_id','v_tickets.status_name', 'v_tickets.expiration', 'v_tickets.created_at',
                    'v_tickets.subject', 'v_tickets.details', 'v_tickets.category',
                    'v_tickets.assigned_user',
                    'v_tickets.store_name',
                    'v_tickets.logger',
                    'v_tickets.ticket_group_name',
                    'v_tickets.times_extended'
                )
                ->when($group, function ($query, $group) {
                    return $query->whereTicketGroupId($group);
                })
                ->when(in_array(strtolower($status), array_map('strtolower', $statuses), true), function ($query) use ($status) {
                    $get_status = Status::where('name', $status)->firstOrFail();
                    return $query->whereStatusId($get_status->id);
                })
                ->when($status === 'my', function ($query) {
                    return $query->whereAssigneeId(Auth::id())->where('status_id', '!=', 3);
                })
                ->when($status === 'fixed', function ($query) {



                    return $query->whereStatusId(4)
                        ->leftJoinSub(DB::table('v_latest_fixes'), 'fixed_details', function ($join) {
                            $join->on('v_tickets.id', '=', 'fixed_details.ticket_id');
                        })->leftJoin('users as fixer', 'fixed_details.fixed_by', 'fixer.id')
                        ->addSelect(DB::raw('CONCAT(fixer.fName," ",fixer.lName) as fixed_by'), 'fix_date');

                })
                ->when($status === 'closed',function ($query){
                    return $query->join('v_resolves','v_tickets.id','v_resolves.ticket_id')
                        ->addSelect('resolver','resolved_date');
                });
        }
        return DataTables::of($query)->toJson();
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
        ->leftjoin('incidents', 'tickets.issue_id','incidents.id')
        ->selectRaw('system_data_corrections.id,system_data_corrections.sdc_no ,tickets.id as ticket_id ,incidents.subject, system_data_corrections.requestor_name, system_data_corrections.dept_supervisor ,system_data_corrections.department, system_data_corrections.position, system_data_corrections.date_submitted, system_data_corrections.posted ');
        $query = $query->orderBy('system_data_corrections.created_at', 'desc');
        $datatablesJSON = DataTables::of($query);
        return $datatablesJSON->make(true);
    }

    public function system($status)
    {


        $query = DB::table('system_data_corrections')
        ->join('tickets', 'system_data_corrections.ticket_no', 'tickets.id')
        ->leftjoin('incidents', 'tickets.issue_id','incidents.id')
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
            ->leftjoin('incidents', 'tickets.issue_id', 'incidents.id')
            ->selectRaw('manual_data_corrections.id,manual_data_corrections.mdc_no ,tickets.id as ticket_id ,incidents.subject, manual_data_corrections.requestor_name ,manual_data_corrections.department, manual_data_corrections.position, manual_data_corrections.date_submitted, manual_data_corrections.posted');
        $query = $query->orderBy('manual_data_corrections.created_at', 'desc');
        $datatablesJSON = DataTables::of($query);
        return $datatablesJSON->make(true);
    }

    public function treasury($status)
    {
        $query = DB::table('system_data_corrections')
            ->join('tickets', 'system_data_corrections.ticket_no', 'tickets.id')
            ->leftjoin('incidents', 'tickets.issue_id', 'incidents.id')
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
        return DataTables::of($query)->toJson();
    }

    public function govcomp($status)
    {
        $query = DB::table('system_data_corrections')
            ->join('tickets', 'system_data_corrections.ticket_no', 'tickets.id')
            ->leftjoin('incidents', 'tickets.issue_id', 'incidents.id')
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
            ->leftjoin('incidents', 'tickets.issue_id', 'incidents.id')
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

    public function devProjects(){
        $query = DB::table('dev_projects as dp')
        ->selectRaw('dp.id,dp.project_name,dp.assigned_to,dp.status,dp.date_start,dp.date_end,dp.md50_status');
        $query = $query->whereNull('deleted_at');
        $datatablesJSON = DataTables::of($query);
        return $datatablesJSON->make(true);
    }  

    public function storeVisit($table){
        if($table === 'targets'){
            $model = StoreVisitTarget::all('id','month','year','num_of_stores','created_at');
        }else if($table === 'details'){
            $model = StoreVisitDetail::with(['status:id,name','store:id,store_name'])
                ->join('users as u','it_personnel','u.id')
                ->select(
            'store_visit_details.id',
                    'store_visit_details.store_id',
                    'status_id',
                    'start_date',
                    'end_date','store_visit_details.created_at'
                    ,DB::raw('CONCAT_WS(" ",fname,lName) as full_name')
                )
                ->get();
        }else{
            die('store visit table not found!');
        }

        return DataTables::of($model)->toJson();
    }

    public function addMDSissue(){
        $query = DB::table('master_data_issues as mdi')
        ->selectRaw('mdi.issue_name,mdi.status,mdi.start_date,mdi.end_date,mdi.logged_by,mdi.id');
        $query = $query->whereNull('deleted_at');
        $datatablesJSON = DataTables::of($query);
        return $datatablesJSON->make(true);
    }
}
