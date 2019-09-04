<?php

namespace App\Providers;

use App\CategoryA;
use App\CategoryB;
use App\CategoryC;
use App\Department;
use App\Email;
use App\Http\Resources\UserResource;
use App\Position;
use App\Status;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use App\Caller;
use App\Category;
use App\CategoryGroup;
use App\Role;
use App\Store;
use App\Ticket;
use App\User;
use App\SystemDataCorrection;
use App\ManualDataCorrection;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        view()->composer('*',function($view){

            $ticket_status_arr = Status::all(['id','name'])->pluck('id','name')->toArray();
            $store_visit_status_arr = Status::whereIn('id',[2,3])->pluck('name','id')->toArray();
            $user_roles = array('admin'=> 4,'user'=> 3,'1support'=> 1,'tower'=> 2);
            $higherUserGroup = array($user_roles['admin'],$user_roles['tower']);
            $view->with(compact(
                'ticket_status_arr',
                'user_roles',
                    'higherUserGroup',
                    'store_visit_status_arr'
                )
            );
        });

        view()->composer(['includes.header', 'ticket.ticket_lookup'], function ($view) {
            $userID = Auth::id();
            $notificationContent = getNotificationContent($userID);
            $dcRoutes = ['datacorrections.system', 'datacorrections.manual', 'datacorrectons.sdcDeployment', 'datacorrectons.sdcTreasury1', 'datacorrectons.sdcTreasury2', 'datacorrectons.sdcGovComp', 'datacorrectons.sdcFinalApp', 'datacorrectons.sdcDraft', 'datacorrectons.sdcDone', 'datacorrectons.sdcRejected', 'datacorrectons.sdcAll'];
            $ticketRoutes = ['openTickets', 'myTickets', 'ongoingTickets', 'closedTickets', 'allTickets'];
            $tyRoutes = ['datacorrectons.treasuryALL', 'datacorrectons.treasuryDONE', 'datacorrectons.treasuryPENDING'];
            $ty2Routes = ['datacorrectons.treasury2ALL', 'datacorrectons.treasury2DONE', 'datacorrectons.treasury2PENDING'];
            $gcRoutes = ['datacorrectons.govcompALL', 'datacorrectons.govcompDONE', 'datacorrectons.govcompPENDING'];
            $appRoutes = ['datacorrectons.approverALL', 'datacorrectons.approverDONE', 'datacorrectons.approverPENDING'];

            $ticketCounts = getNumberOfTicketsOnASpecStatus();
            $ticketUserTicketsCount = Ticket::whereAssignee($userID)->where('status','!=',3)->count();
            $view->with(compact(
                'ticketCounts',
                'ticketUserTicketsCount',
                'ticketRoutes',
                'dcRoutes',
                'notificationContent',
                'tyRoutes',
                'ty2Routes',
                'dcRoutes',
                'gcRoutes',
                'appRoutes'
            ));
        });


        view()->composer('modal.fix_form', function ($view) {

            $resolutionOptions = DB::table('resolve_categories')->pluck('name', 'id')->toArray();  /*Resolve*/

            $view->with([
                'resolutionOptions' => $resolutionOptions
            ]);
        });

        view()->composer(['ticket.add_ticket', 'modal.ticket_edit', 'modal.user_add', 'ticket.incomplete', 'modal.r_ticket','maintenance','includes.ticket_filter'], function ($view) {
            $selfOption = [null => 'None', Auth::id() => 'Self'];
            $statusSelect = DB::table('ticket_status')->pluck('name', 'id')->toArray();  /*Status*/
            $prioSelect = DB::table('priorities')->pluck('name', 'id')->toArray();   /*Priority*/
            $typeSelect = DB::table('categories')->where('name','!=','Connection')->pluck('name', 'id')->toArray();   /*Incident category*/
            $incBSelect = DB::table('category_b')->whereNotIn('id',[16,17,18])->pluck('name', 'id')->toArray(); /*A Sub category for incident*/
            $groupSelect = DB::table('ticket_groups')->pluck('name', 'id')->toArray(); /*TICKET_GROUP*/
            $rolesSelect = selectArray('', Role::class, 'id', 'role'); /*Roles*/
            $positionsSelect = selectArray('', Position::class, 'id', 'position'); /*Roles*/
            $departmentSelect = selectArray('', Department::class, 'id', 'department'); /*Roles*/
            $callerSelect = Caller::get()->pluck('name', 'id');
            $categoryBGroupSelect = groupListSelectArray(CategoryA::class, 'name', 'subCategories', 'id', 'name',array('column' => 'id','values' => [6]));
            $categoryCConnectionGroupSelect = groupListSelectArray(CategoryB::class, 'name', 'subCategories', 'id', 'name',array('column' => 'id','values' => [212,213]));
            $CategoryCSelect = CategoryC::pluck('name', 'id')->toArray();
            $emails = Email::all();
            $emailSelect = $emails->pluck('email','id');
            $emailSelect_connection = $emails->pluck('email','email')->toArray();
            $email_groups = \App\EmailGroup::select(DB::raw('CONCAT_WS("_","group",id) as temp'),'group_name','id')->get(['text','group_name','id']);
            $email_groups_select = $email_groups->pluck('group_name','id')->toArray();
            $email_groups_connection = $email_groups->pluck('group_name','temp')->toArray();
            $emailAndGroupSelect = array_merge($emailSelect_connection,$email_groups_connection);
            $branchSelect = Store::all()->pluck('store_name', 'id')->toArray();
            $cons = array(
                'column'=>'id',
                'values'=> [3,4,5,6,7,8]
            );
            $assigneeSelect = groupListSelectArray(Role::class, 'role', 'users', 'id', 'full_name', $cons);
            $filterAssigneeSelect = groupListSelectArray(Role::class, 'role', 'users', 'full_name', 'full_name');
            $categoryASelect = CategoryA::pluck('name','id')->toArray();
            $usersSelect = User::all()->pluck('full_name','id')->toArray();
            $view->with(compact(
                'statusSelect',
                'prioSelect',
                'typeSelect',
                'incBSelect',
                'callerSelect',
                'branchSelect',
                'assigneeSelect',
                'rolesSelect',
                'positionsSelect',
                'departmentSelect',
                'categoryBGroupSelect',
                'selfOption',
                'groupSelect',
                'CategoryCSelect',
                'emailAndGroupSelect',
                'categoryCConnectionGroupSelect',
                'email_groups_select',
                'emailSelect',
                'filterAssigneeSelect',
                'categoryASelect',
                'usersSelect'
            ));
        });


        view()->composer('includes.ticket_filter', function ($view) {

            $categoryFilter = DB::table('categories')->pluck('name', 'name');
            $statusFilter = DB::table('ticket_status')->pluck('name', 'name');
            $storeFilter = Store::pluck('store_name', 'store_name');

            $view->with(compact('categoryFilter', 'statusFilter', 'storeFilter'));
        });

        view()->composer('datacorrections.systemdcs',function($view){
           $sdcCount = SystemDataCorrection::all()->count();
           $mdcCount = ManualDataCorrection::all()->count();
          
           $ty1Count = SystemDataCorrection::where('forward_status','=',1)->where('status','=',1)->count();
           $ty2Count = SystemDataCorrection::where('forward_status','=',2)->where('status','=',1)->count();
           $govcompCount = SystemDataCorrection::where('forward_status','=',3)->where('status','=',1)->count();
           $finalAppCount = SystemDataCorrection::where('forward_status','=',4)->where('status','=',1)->count();

           $forDeploymentCount = SystemDataCorrection::where('status', '=', 3)->count();
           $doneCount = SystemDataCorrection::where('status', '=', 4)->count();
           $rejectedCount = SystemDataCorrection::where('status', '=', 2)->count();
           $draftCount = SystemDataCorrection::where('status','=',0)->count();
           

           $view->with(['sdcCount'=> $sdcCount, 'mdcCount'=>$mdcCount, 'draftCount'=>$draftCount ,'ty1Count'=>$ty1Count, 'ty2Count'=>$ty2Count, 'govcompCount'=>$govcompCount, 'finalAppCount'=>$finalAppCount,'forDeploymentCount'=>$forDeploymentCount ,'doneCount'=>$doneCount,'rejectedCount'=>$rejectedCount,
           'dcRoutes' => ['datacorrections.system', 'datacorrections.manual', 'datacorrectons.sdcDeployment', 'datacorrectons.sdcTreasury1', 'datacorrectons.sdcTreasury2', 'datacorrectons.sdcGovComp', 'datacorrectons.sdcFinalApp', 'datacorrectons.sdcDraft', 'datacorrectons.sdcDone', 'datacorrectons.sdcRejected', 'datacorrectons.sdcAll']
           ]);
        });


        view()->composer('datacorrections.manualdcs', function ($view) {
            $mdcCount = ManualDataCorrection::all()->count();
            $sdcCount = SystemDataCorrection::all()->count();
            $view->with(['mdcCount' => $mdcCount, 'sdcCount' => $sdcCount]);
        });

        view()->composer('datacorrections.datacorrections', function ($view) {
            $mdcCount = ManualDataCorrection::all()->count();
            $sdcCount = SystemDataCorrection::all()->count();
            $view->with(['mdcCount'=>$mdcCount, 'sdcCount'=>$sdcCount]);
         });



         view()->composer('datacorrections.govcomp',function($view){
            $pendingCount = SystemDataCorrection::where('status',1)->where('forward_status', 3)->count();
            $doneCount = SystemDataCorrection::whereIn('status', array(1,3,4))->whereIn('forward_status', array(4,5))->count();
            $allCount = SystemDataCorrection::whereIn('status', array(1,3,4))->whereIn('forward_status', array(3,4,5))->count();
            $view->with(['pendingCount'=>$pendingCount, 'doneCount'=>$doneCount, 'allCount'=> $allCount]);
         });


         view()->composer('datacorrections.approver',function($view){
            $pendingCount = SystemDataCorrection::where('status', 1)->where('forward_status',4)->count();
            $doneCount = SystemDataCorrection::whereIn('status',  array(1,3,4))->where('forward_status', 5)->count();
            $allCount = SystemDataCorrection::whereIn('status',  array(1,3,4))->whereIn('forward_status', array(4,5))->count();
            $view->with(['pendingCount'=>$pendingCount, 'doneCount'=>$doneCount, 'allCount'=> $allCount]);
         });

         view()->composer('datacorrections.treasury',function($view){
            $pendingCount = SystemDataCorrection::where('status', 1)->where('forward_status',1)->count();
            $doneCount = SystemDataCorrection::whereIn('status', array(1,3,4))->whereIn('forward_status', array(2,3,4,5))->count();
            $allCount = SystemDataCorrection::whereIn('status', array(1,3,4))->whereIn('forward_status', array(1,2,3,4,5))->count();
            $view->with(['pendingCount'=>$pendingCount, 'doneCount'=>$doneCount, 'allCount'=> $allCount]);
         });

         view()->composer('datacorrections.treasury2',function($view){
            $pendingCount = SystemDataCorrection::where('status', 1)->where('forward_status',2)->count();
            $doneCount = SystemDataCorrection::whereIn('status', array(1,3,4))->whereIn('forward_status', array(3,4,5))->count();
            $allCount = SystemDataCorrection::whereIn('status', array(1,3,4))->whereIn('forward_status', array(2,3,4,5))->count();
            $view->with(['pendingCount'=>$pendingCount, 'doneCount'=>$doneCount, 'allCount'=> $allCount]);
         });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
