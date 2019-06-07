<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MasterDataIssue;
use Auth;


class MDSIssuesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        MasterDataIssue::create([
            'issue_name'=>$request->issueName,
            'status'=>$request->status,
            'start_date'=>$request->dateStart,
            'end_date'=>$request->dateEnd,
            'logged_by'=>Auth::user()->id
        ]);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('master_data.master_data_issues');
    }

    public function showEdit($id){
        $issue = MasterDataIssue::find($id);
        return view('modal.edit_mdIssue', ['issue'=>$issue]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    { 
        $issue = MasterDataIssue::find($request->issueID);
        $issue->issue_name = $request->issueName;
        $issue->status = $request->status;
        $issue->start_date = $request->dateStart;
        $issue->end_date = $request->dateEnd;
        $issue->save();
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        date_default_timezone_set("Asia/Manila");
        $currentDate =  date('Y-m-d H:i:s');
        $issue = MasterDataIssue::findorFail($id);
        $issue->deleted_at = $currentDate;
        $issue->save();
        return redirect()->back();
    }
}
