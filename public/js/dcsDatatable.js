 

$(function() {
    
    $('#mdc-table').DataTable({
        dom: 'lrtip',
        processing: true,
        serverSide: true,
        order: [[4, 'desc']],
        "bPaginate": true,
        "sPaginationType": "full_numbers",
        ajax: '/datacorrections/mdc',
        columns: [
            { data: 'subject', name: 'subject' },
            { data: 'requestor_name', name: 'requestor_name' },
            { data: 'dept_supervisor', name: 'dept_supervisor' },
            { data: 'department', name: 'department' },
            { data: 'position', name: 'position' },
            { data: 'date_submitted', name: 'date_submitted' },
            { data: 'ticket_id', name: 'ticket_id', visible: false},
            { data: 'sdc_no', name: 'sdc_no', visible: false}
        ],
         columnDefs: [
                { 
                  orderable: false, 
                  targets: [0,1,2,3]
                },
                {
                    targets: 0, /*SUBJECT*/
                    createdCell: ( cell, cellData,rowData) => {
                        cell.setAttribute('title',rowData.subject);
                    },
                    orderable: false,
                    render: (data, type, row) => {
                        var posted = "UNPOSTED";
                        if(row.posted == 1){
                            posted = "POSTED";
                        }
                        return `<a href='/manual/${row.id}/print/' class='table__subject' target='_blank'>${row.subject}</a><br>
                        <span class='table__info'>Ticket #: ${row.ticket_id}</span>
                        <span class='table__info'>MDC #: ${row.id}</span>\t
                        <span class='table__info'>${posted} </span>
                         `
                    }

                 }  
         ]

    });
    
    try{
        let dcStats;
        let dcStatsRegex = new RegExp('\/datacorrections\/sdc\/([a-z0-9]+)', 'gm');
        dcStats = dcStatsRegex.exec(window.location.pathname)[1];
        
       var table =  $('#sdc-table').DataTable({
            dom: 'lrtip',
            processing: true,
            serverSide: true,
            order: [[5, 'desc']],
            "bPaginate": true,
            "sPaginationType": "full_numbers",
            ajax: '/datacorrections/sdc-data/'+ dcStats,
            columns: [
                { data: 'subject', name: 'subject' },
                { data: 'requestor_name', name: 'requestor_name' },
                { data: 'dept_supervisor', name: 'dept_supervisor' },
                { data: 'department', name: 'department' },
                { data: 'position', name: 'position' },
                { data: 'date_submitted', name: 'date_submitted' },
                { data: 'ticket_id', name: 'ticket_id', visible: false},
                { data: 'sdc_no', name: 'sdc_no', visible: false},
                { data: 'forward_status', name: 'forward_status', visible: false}
            ],
            columnDefs: [
                    { 
                    orderable: false, 
                    targets: [0,1,2,3,4]
                    },
                    {
                        targets: 0, /*SUBJECT*/
                        createdCell: ( cell, cellData,rowData) => {
                            cell.setAttribute('title',rowData.subject);
                        },
                        orderable: false,
                        render: (data, type, row) => {
                            var status = "";    
                            if(row.forward_status == 1 && row.status == 1){
                                status = "TREASURY 1";
                            }else if(row.forward_status == 2  && row.status == 1){
                                status = "TREASURY 2";
                            }else if(row.forward_status == 3  && row.status == 1){
                                status = "GOV. COMPLIANCE";
                            }else if(row.forward_status == 4  && row.status == 1){
                                status = "FINAL APPROVER";
                            }else if(row.forward_status == 5  && row.status == 3){
                                status = "FOR DEPLOYMENT";
                            }else if(row.status == 4){
                                status = "DONE";
                            }else if(row.status == 2){
                                status = "REJECTED";
                            }else if(row.status == 0){
                                status = "DRAFT";
                            }
                            if((row.forward_status >= 1 || row.forward_status == 5) && (row.status == 1 || row.status == 4)){
                                return `<a href='/system/${row.id}/print/' class='table__subject' target='_blank'>${row.subject}</a><br>
                                <span class='table__info'>Ticket #: ${row.ticket_id}</span>
                                <span class='table__info'>SDC #: ${row.id}</span>\t
                                <span class='table__info'>${status} </span>
                                `
                            }else{
                                return `<a href='/sdc/${row.id}/edit/' class='table__subject'>${row.subject}</a><br>
                                <span class='table__info'>Ticket #: ${row.ticket_id}</span>
                                <span class='table__info'>SDC #: ${row.id}</span>\t
                                <span class='table__info'>${status} </span>
                                `
                            }
                          
                        }

                    }  
            ]

        });
        var timer = null;

        $('#search').on('keyup', function () {
            clearTimeout(timer); 
            timer = setTimeout(function(){
                var inputValue = $("#search").val();
                table.column(0).search(inputValue, true, false).draw();     
        }, 500)
        });

      
        $.fn.dataTable.ext.errMode = 'throw';
    }catch(err){
        
    }
        
   



// END OF SUPPORT SDC DATATABLE PERSPECTIVE



 //  START OF TREASURY TABLE OPTIONS
 
    const allTreasury = {
        dom: 'lrtip',
        processing: true,
        serverSide: true,
        order: [[5, 'desc']],
        "bPaginate": true,
        "sPaginationType": "full_numbers",
        ajax: '/datacorrections/ty-data/all',
        columns: [
            { data: 'subject', name: 'subject' },
            { data: 'requestor_name', name: 'requestor_name' },
            { data: 'dept_supervisor', name: 'dept_supervisor' },
            { data: 'department', name: 'department' },
            { data: 'position', name: 'position' },
            { data: 'date_submitted', name: 'date_submitted' },
            { data: 'ticket_id', name: 'ticket_id', visible: false},
            { data: 'sdc_no', name: 'sdc_no', visible: false}
        ],
         columnDefs: [
                { 
                  orderable: false, 
                  targets: [0,1,2,3,4]
                },
                {
                    targets: 0, /*SUBJECT*/
                    createdCell: ( cell, cellData,rowData) => {
                        cell.setAttribute('title',rowData.subject);
                    },
                    orderable: false,
                    render: (data, type, row) => {
                        var status = "PENDING";
                        if(row.forward_status == 1){
                            status = "PENDING";
                            return `<a href='/sdc/${row.id}/edit/' class='table__subject'>${row.subject}</a><br>
                            <span class='table__info'>Ticket #: ${row.ticket_id}</span>
                            <span class='table__info'>SDC #: ${row.id}</span>\t
                            <span class='table__info'>${status} </span>
                             `
                        }else if(row.forward_status >= 2){
                            status = "DONE";
                            return `<a href='/system/${row.id}/print/' class='table__subject' target='_blank'>${row.subject}</a><br>
                            <span class='table__info'>Ticket #: ${row.ticket_id}</span>
                            <span class='table__info'>SDC #: ${row.id}</span>\t
                            <span class='table__info'>${status} </span>
                             `
                        }

                    
                      
                    }

                 }  
         ]
    };


    
    const allTreasury2 = {
        dom: 'lrtip',
        processing: true,
        serverSide: true,
        order: [[5, 'desc']],
        "bPaginate": true,
        "sPaginationType": "full_numbers",
        ajax: '/datacorrections/ty-data/all',
        columns: [
            { data: 'subject', name: 'subject' },
            { data: 'requestor_name', name: 'requestor_name' },
            { data: 'dept_supervisor', name: 'dept_supervisor' },
            { data: 'department', name: 'department' },
            { data: 'position', name: 'position' },
            { data: 'date_submitted', name: 'date_submitted' },
            { data: 'ticket_id', name: 'ticket_id', visible: false},
            { data: 'sdc_no', name: 'sdc_no', visible: false}
        ],
         columnDefs: [
                { 
                  orderable: false, 
                  targets: [0,1,2,3,4]
                },
                {
                    targets: 0, /*SUBJECT*/
                    createdCell: ( cell, cellData,rowData) => {
                        cell.setAttribute('title',rowData.subject);
                    },
                    orderable: false,
                    render: (data, type, row) => {
                        var status = "PENDING";
                        if(row.forward_status == 2){
                            status = "PENDING";
                            return `<a href='/sdc/${row.id}/edit/' class='table__subject'>${row.subject}</a><br>
                            <span class='table__info'>Ticket #: ${row.ticket_id}</span>
                            <span class='table__info'>SDC #: ${row.id}</span>\t
                            <span class='table__info'>${status} </span>
                             `
                        }else if(row.forward_status >= 3){
                            status = "DONE";
                            return `<a href='/system/${row.id}/print/' class='table__subject' target='_blank'>${row.subject}</a><br>
                            <span class='table__info'>Ticket #: ${row.ticket_id}</span>
                            <span class='table__info'>SDC #: ${row.id}</span>\t
                            <span class='table__info'>${status} </span>
                             `
                        }

                    
                      
                    }

                 }  
         ]
    };

    const pendingTreasury = {
        dom: 'lrtip',
        processing: true,
        serverSide: true,
        order: [[5, 'desc']],
        "bPaginate": true,
        "sPaginationType": "full_numbers",
        ajax: '/datacorrections/ty-data/pending',
        columns: [
            { data: 'subject', name: 'subject' },
            { data: 'requestor_name', name: 'requestor_name' },
            { data: 'dept_supervisor', name: 'dept_supervisor' },
            { data: 'department', name: 'department' },
            { data: 'position', name: 'position' },
            { data: 'date_submitted', name: 'date_submitted' },
            { data: 'ticket_id', name: 'ticket_id', visible: false},
            { data: 'sdc_no', name: 'sdc_no', visible: false}
        ],
         columnDefs: [
                { 
                  orderable: false, 
                  targets: [0,1,2,3,4]
                },
                {
                    targets: 0, /*SUBJECT*/
                    createdCell: ( cell, cellData,rowData) => {
                        cell.setAttribute('title',rowData.subject);
                    },
                    orderable: false,
                    render: (data, type, row) => {
                        var status = "PENDING";
                       
                        return `<a href='/sdc/${row.id}/edit/' class='table__subject'>${row.subject}</a><br>
                        <span class='table__info'>Ticket #: ${row.ticket_id}</span>
                        <span class='table__info'>SDC #: ${row.id}</span>\t
                        <span class='table__info'>${status} </span>
                         `
                    }

                 }  
         ]
    };

    const doneTreasury = {
        dom: 'lrtip',
        processing: true,
        serverSide: true,
        order: [[5, 'desc']],
        "bPaginate": true,
        "sPaginationType": "full_numbers",
        ajax: '/datacorrections/ty-data/done',
        columns: [
            { data: 'subject', name: 'subject' },
            { data: 'requestor_name', name: 'requestor_name' },
            { data: 'dept_supervisor', name: 'dept_supervisor' },
            { data: 'department', name: 'department' },
            { data: 'position', name: 'position' },
            { data: 'date_submitted', name: 'date_submitted' },
            { data: 'ticket_id', name: 'ticket_id', visible: false},
            { data: 'sdc_no', name: 'sdc_no', visible: false}
        ],
         columnDefs: [
                { 
                  orderable: false, 
                  targets: [0,1,2,3,4]
                },
                {
                    targets: 0, /*SUBJECT*/
                    createdCell: ( cell, cellData,rowData) => {
                        cell.setAttribute('title',rowData.subject);
                    },
                    orderable: false,
                    render: (data, type, row) => {
                      
                        status = "DONE";
                       
                        return `<a href='/system/${row.id}/print/' class='table__subject' target='_blank'>${row.subject}</a><br>
                        <span class='table__info'>Ticket #: ${row.ticket_id}</span>
                        <span class='table__info'>SDC #: ${row.id}</span>\t
                        <span class='table__info'>${status} </span>
                         `
                    }

                 }  
         ]
    };
// END OF TREASURY TABLE OPTIONS

//  START OF GOV.COMP TABLE OPTIONS 

    const pendingGC = {
        dom: 'lrtip',
        processing: true,
        serverSide: true,
        order: [[5, 'desc']],
        "bPaginate": true,
        "sPaginationType": "full_numbers",
        ajax: '/datacorrections/gc-data/pending',
        columns: [
            { data: 'subject', name: 'subject' },
            { data: 'requestor_name', name: 'requestor_name' },
            { data: 'dept_supervisor', name: 'dept_supervisor' },
            { data: 'department', name: 'department' },
            { data: 'position', name: 'position' },
            { data: 'date_submitted', name: 'date_submitted' },
            { data: 'ticket_id', name: 'ticket_id', visible: false},
            { data: 'sdc_no', name: 'sdc_no', visible: false}
        ],
         columnDefs: [
                { 
                  orderable: false, 
                  targets: [0,1,2,3,4]
                },
                {
                    targets: 0, /*SUBJECT*/
                    createdCell: ( cell, cellData,rowData) => {
                        cell.setAttribute('title',rowData.subject);
                    },
                    orderable: false,
                    render: (data, type, row) => {
                        var status = "PENDING";
                      
                        return `<a href='/sdc/${row.id}/edit/' class='table__subject'>${row.subject}</a><br>
                        <span class='table__info'>Ticket #: ${row.ticket_id}</span>
                        <span class='table__info'>SDC #: ${row.id}</span>\t
                        <span class='table__info'>${status} </span>
                         `
                    }

                 }  
         ]
    };

    const doneGC = {
        dom: 'lrtip',
        processing: true,
        serverSide: true,
        order: [[5, 'desc']],
        "bPaginate": true,
        "sPaginationType": "full_numbers",
        ajax: '/datacorrections/gc-data/done',
        columns: [
            { data: 'subject', name: 'subject' },
            { data: 'requestor_name', name: 'requestor_name' },
            { data: 'dept_supervisor', name: 'dept_supervisor' },
            { data: 'department', name: 'department' },
            { data: 'position', name: 'position' },
            { data: 'date_submitted', name: 'date_submitted' },
            { data: 'ticket_id', name: 'ticket_id', visible: false},
            { data: 'sdc_no', name: 'sdc_no', visible: false}
        ],
         columnDefs: [
                { 
                  orderable: false, 
                  targets: [0,1,2,3,4]
                },
                {
                    targets: 0, /*SUBJECT*/
                    createdCell: ( cell, cellData,rowData) => {
                        cell.setAttribute('title',rowData.subject);
                    },
                    orderable: false,
                    render: (data, type, row) => {
                        var status = "DONE";
                      
                        return `<a href='/system/${row.id}/print/' class='table__subject' target='_blank'>${row.subject}</a><br>
                        <span class='table__info'>Ticket #: ${row.ticket_id}</span>
                        <span class='table__info'>SDC #: ${row.id}</span>\t
                        <span class='table__info'>${status} </span>
                         `
                    }

                 }  
         ]
    };

    const allGC = {
        dom: 'lrtip',
        processing: true,
        serverSide: true,
        order: [[5, 'desc']],
        "bPaginate": true,
        "sPaginationType": "full_numbers",
        ajax: '/datacorrections/gc-data/all',
        columns: [
            { data: 'subject', name: 'subject' },
            { data: 'requestor_name', name: 'requestor_name' },
            { data: 'dept_supervisor', name: 'dept_supervisor' },
            { data: 'department', name: 'department' },
            { data: 'position', name: 'position' },
            { data: 'date_submitted', name: 'date_submitted' },
            { data: 'ticket_id', name: 'ticket_id', visible: false},
            { data: 'sdc_no', name: 'sdc_no', visible: false}
        ],
         columnDefs: [
                { 
                  orderable: false, 
                  targets: [0,1,2,3,4]
                },
                {
                    targets: 0, /*SUBJECT*/
                    createdCell: ( cell, cellData,rowData) => {
                        cell.setAttribute('title',rowData.subject);
                    },
                    orderable: false,
                    render: (data, type, row) => {
                        var status = "PENDING";
                        if(row.forward_status == 3){
                            status = "PENDING";
                            return `<a href='/sdc/${row.id}/edit/' class='table__subject'>${row.subject}</a><br>
                            <span class='table__info'>Ticket #: ${row.ticket_id}</span>
                            <span class='table__info'>SDC #: ${row.id}</span>\t
                            <span class='table__info'>${status} </span>
                             `

                        }else if(row.forward_status >= 4){
                            status = "DONE"
                            return `<a href='/system/${row.id}/print/' class='table__subject' target='_blank'>${row.subject}</a><br>
                            <span class='table__info'>Ticket #: ${row.ticket_id}</span>
                            <span class='table__info'>SDC #: ${row.id}</span>\t
                            <span class='table__info'>${status} </span>
                             `
                        }
                     
                    }

                 }  
         ]
    };

// END OF GOV. COMP TABLE OPTIONS

// START OF APPROVER TABLE OPTIONS
    const pendingAPP = {
        dom: 'lrtip',
        processing: true,
        serverSide: true,
        order: [[5, 'desc']],
        "bPaginate": true,
        "sPaginationType": "full_numbers",
        ajax: '/datacorrections/app-data/pending',
        columns: [
            { data: 'subject', name: 'subject' },
            { data: 'requestor_name', name: 'requestor_name' },
            { data: 'dept_supervisor', name: 'dept_supervisor' },
            { data: 'department', name: 'department' },
            { data: 'position', name: 'position' },
            { data: 'date_submitted', name: 'date_submitted' },
            { data: 'ticket_id', name: 'ticket_id', visible: false},
            { data: 'sdc_no', name: 'sdc_no', visible: false}
        ],
         columnDefs: [
                { 
                  orderable: false, 
                  targets: [0,1,2,3,4]
                },
                {
                    targets: 0, /*SUBJECT*/
                    createdCell: ( cell, cellData,rowData) => {
                        cell.setAttribute('title',rowData.subject);
                    },
                    orderable: false,
                    render: (data, type, row) => {
                        var status = "PENDING";
                        return `<a href='/sdc/${row.id}/edit/' class='table__subject'>${row.subject}</a><br>
                        <span class='table__info'>Ticket #: ${row.ticket_id}</span>
                        <span class='table__info'>SDC #: ${row.id}</span>\t
                        <span class='table__info'>${status} </span>
                         `
                    }

                 }  
         ]
    };

    const doneApp = {
        dom: 'lrtip',
        processing: true,
        serverSide: true,
        order: [[5, 'desc']],
        "bPaginate": true,
        "sPaginationType": "full_numbers",
        ajax: '/datacorrections/app-data/done',
        columns: [
            { data: 'subject', name: 'subject' },
            { data: 'requestor_name', name: 'requestor_name' },
            { data: 'dept_supervisor', name: 'dept_supervisor' },
            { data: 'department', name: 'department' },
            { data: 'position', name: 'position' },
            { data: 'date_submitted', name: 'date_submitted' },
            { data: 'ticket_id', name: 'ticket_id', visible: false},
            { data: 'sdc_no', name: 'sdc_no', visible: false}
        ],
         columnDefs: [
                { 
                  orderable: false, 
                  targets: [0,1,2,3,4]
                },
                {
                    targets: 0, /*SUBJECT*/
                    createdCell: ( cell, cellData,rowData) => {
                        cell.setAttribute('title',rowData.subject);
                    },
                    orderable: false,
                    render: (data, type, row) => {
                        var status = "DONE";
                    
                        return `<a href='/system/${row.id}/print/' class='table__subject' target='_blank'>${row.subject}</a><br>
                        <span class='table__info'>Ticket #: ${row.ticket_id}</span>
                        <span class='table__info'>SDC #: ${row.id}</span>\t
                        <span class='table__info'>${status} </span>
                         `
                    }

                 }  
         ]
    };

    const allAPP = {
        dom: 'lrtip',
        processing: true,
        serverSide: true,
        order: [[5, 'desc']],
        "bPaginate": true,
        "sPaginationType": "full_numbers",
        ajax: '/datacorrections/app-data/all',
        columns: [
            { data: 'subject', name: 'subject' },
            { data: 'requestor_name', name: 'requestor_name' },
            { data: 'dept_supervisor', name: 'dept_supervisor' },
            { data: 'department', name: 'department' },
            { data: 'position', name: 'position' },
            { data: 'date_submitted', name: 'date_submitted' },
            { data: 'ticket_id', name: 'ticket_id', visible: false},
            { data: 'sdc_no', name: 'sdc_no', visible: false}
        ],
         columnDefs: [
                { 
                  orderable: false, 
                  targets: [0,1,2,3,4]
                },
                {
                    targets: 0, /*SUBJECT*/
                    createdCell: ( cell, cellData,rowData) => {
                        cell.setAttribute('title',rowData.subject);
                    },
                    orderable: false,
                    render: (data, type, row) => {
                        var status = "";
                        if(row.forward_status == 4){
                            status = "PENDING";
                            return `<a href='/sdc/${row.id}/edit/' class='table__subject'>${row.subject}</a><br>
                            <span class='table__info'>Ticket #: ${row.ticket_id}</span>
                            <span class='table__info'>SDC #: ${row.id}</span>\t
                            <span class='table__info'>${status} </span>
                             `
                        }else if(row.forward_status = 5){
                            status = "DONE";
                            return `<a href='/system/${row.id}/print/' class='table__subject' target='_blank'>${row.subject}</a><br>
                            <span class='table__info'>Ticket #: ${row.ticket_id}</span>
                            <span class='table__info'>SDC #: ${row.id}</span>\t
                            <span class='table__info'>${status} </span>
                             `
                        }
                      
                    }

                 }  
         ]
    };
// END OF APPROVER TABLE OPTIONS
try {
    

    let dcUser,dcStatus;
    let dcURegex = new RegExp('\/datacorrections\/([a-z0-9]+)', 'gm');
    dcUser = dcURegex.exec(window.location.pathname)[1];

    switch(dcUser){
        case 'ty':
            dcRegex = new RegExp('\/datacorrections\/ty\/sdc\/([a-z]+)', 'gm');
            dcStatus = dcRegex.exec(window.location.pathname)[1];

            switch(dcStatus){
                case "pending":
                    $('#treasury-table').DataTable(pendingTreasury);
                break;
                case "done":
                    $('#treasury-table').DataTable(doneTreasury);
                break;
                case "all":
                    $('#treasury-table').DataTable(allTreasury);
                break;
            }
        break;
        case 'ty2':
            dcRegex = new RegExp('\/datacorrections\/ty2\/sdc\/([a-z]+)', 'gm');
            dcStatus = dcRegex.exec(window.location.pathname)[1];

            switch(dcStatus){
                case "pending":
                    $('#treasury2-table').DataTable(pendingTreasury);
                break;
                case "done":
                    $('#treasury2-table').DataTable(doneTreasury);
                break;
                case "all":
                    $('#treasury2-table').DataTable(allTreasury2);
                break;
            }
          
        break;
        case 'gc':
            dcRegex = new RegExp('\/datacorrections\/gc\/sdc\/([a-z]+)', 'gm');
            dcStatus = dcRegex.exec(window.location.pathname)[1];

            switch(dcStatus){
                case "pending":
                    $('#gc-table').DataTable(pendingGC);
                break;
                case "done":
                    $('#gc-table').DataTable(doneGC);
                break;
                case "all":
                    $('#gc-table').DataTable(allGC);
                break;
            }
        break;
        case 'app':
        dcRegex = new RegExp('\/datacorrections\/app\/sdc\/([a-z]+)', 'gm');
        dcStatus = dcRegex.exec(window.location.pathname)[1];

        switch(dcStatus){
            case "pending":
                $('#app-table').DataTable(pendingAPP);
            break;
            case "done":
                $('#app-table').DataTable(doneApp);
            break;
            case "all":
                $('#app-table').DataTable(allAPP);
            break;
        }

        break;
    }

} catch (error) {
    
}

//  SUPPORTS DATATABLE

// let dcStats;
// let dcStatsRegex = new RegExp('\/datacorrections\/sdc\/([a-z]+)', 'gm');
// dcStats = dcStatsRegex.exec(window.location.pathname)[1];
 
// switch(dcStats){
//     case "approved":
//         $('#sdc-table').DataTable($sdcapproved);
//     break;

// }

try {
   $('#approver1').on('change',function(){
       var myValue = $(this).val();
        if(myValue == 1){
            $('#approver2').html("<option value='2' selected>Treasury 2</option><option value='3'>Gov. Compliance</option><option value='4'>Final Approver</option>");
            $('#approver3').html("<option value='3' selected>Gov. Compliance</option><option value='4'>Final Approver</option>");
            $('#approver4').html("<option value='4' selected>Final Approver</option>");
        }else if(myValue == 2){
            $('#approver2').html("<option value='3' selected>Gov. Compliance</option><option value='4'>Final Approver</option>");
            $('#approver3').html("<option value='4'>Final Approver</option>");
            $('#approver4').html("<option value=''>-- -- --</option>");
        }else if(myValue == 3){
            $('#approver2').html("<option value='4' selected>Final Approver</option>");
            $('#approver3').html("<option value=''>-- -- --</option>");
            $('#approver4').html("<option value=''>-- -- --</option>");
        }else if(myValue == 4){
            $('#approver2').html("<option value=''>-- -- --</option>");
            $('#approver3').html("<option value=''>-- -- --</option>");
            $('#approver4').html("<option value=''>-- -- --</option>");
        }
   });

   $('#approver2').on('change',function(){
    var myValue = $(this).val();
     if(myValue == 2){
        $('#approver3').html("<option value='3' selected>Gov. Compliance</option><option value='4'>Final Approver</option>");
        $('#approver4').html("<option value='4'>Final Approver</option>");
    }else if(myValue == 3){
        $('#approver3').html("<option value='4' selected>Final Approver</option>");
        $('#approver4').html("<option value=''>-- -- --</option>");
    }else if(myValue == 4){
        $('#approver3').html("<option value=''>-- -- --</option>");
        $('#approver4').html("<option value=''>-- -- --</option>");
    }
   });

   $('#approver3').on('change',function(){
    var myValue = $(this).val();
     if(myValue == 3){
        $('#approver4').html("<option value='4' selected>Final Approver</option>");
    }else if(myValue == 4){
        $('#approver4').html("<option value=''>-- -- --</option>");
    }
   });
} catch (error) {
    
}

// try {
   
//     let sdcID = $('#appStatus').data('sdcid');
//     if(sdcID){
//                 $.ajax({
//                     url : '/get/'+sdcID+'/appdetails',
//                     type : 'GET',
//                     data : {
//                         _token: '{{csrf_token()}}',
//                     },
//                     success : function(data){
//                         $('#appstats_tbody').html(data.data);
//                     }
//                 });      
//     }

// } catch (error) {
    
// }
    
});
