import {
    clearLoader,
    elements, elementStrings,
    insertToModal,
    renderLoader,
    showModal
} from "../views/base";

export const MasterDataController = () => {
    var table =  $('#mds-table').DataTable({
        dom: 'lrtip',
        processing: true,
        serverSide: true,
        order: [[2, 'desc']],
        "bPaginate": true,
        "sPaginationType": "full_numbers",
        ajax: '/get/mdis',
        columns: [
            { data: 'issue_name', name: 'issue_name' },
            { data: 'status', name: 'status' },
            { data: 'start_date', name: 'start_date' },
            { data: 'end_date', name: 'end_date' },
            { data: 'issue_name', name: 'actions' },
            { data: 'id', visible: false}
        ],
         columnDefs: [
                { 
                  orderable: true, 
                  targets: [0,1,2,3]
                },
                {
                    targets: 4, /*ACTIONS*/
                    createdCell: ( cell, cellData,rowData) => {
                        // cell.setAttribute('title',rowData.subject);
                    },
                    orderable: false,
                    render: (data, type, row) => {
                       
                        return `<button data-action='edit' data-rid='${row.id}'   class='btn-edit btn btn--blue' title='Update Details'><i class='fa fa-pen'></i></button> 
                                <button data-action='delete' data-rid='${row.id}' class='btn-delete btn btn--red' title='Delete Issue'><i class='fa fa-trash'></i></button>`
                    }

                 }
                 
         ]
    });
    

    $('#mds-table').on('click', '.btn-edit', (e)=>{ 
        e.preventDefault();
        var rID = $(e.currentTarget).data('rid');
        showModal();
        renderLoader(elements.modalContent);
        $.ajax({
            url : '/show/'+rID+'/editmdissue',
            type: 'GET',
            data : {},
            success : function(data){
             clearLoader();
             insertToModal(data);
            }
        });
    });

    $('#mds-table').on('click', '.btn-delete', (e)=>{ 
        var yesno = confirm("You are about to delete this issue, Are you sure ?");
        var rID = $(e.currentTarget).data('rid');
        if (yesno){
             $.ajax({
                 url : '/delete/'+rID+'/mdis',
                 type: 'GET',
                 data : {},
                 success : function(data){
                     window.alert("Issue successfully deleted !");
                     location.reload();
                 }
             });
        }
    });

    var timer = null;

   $('#mdssearch').on('keyup', function () {
       clearTimeout(timer); 
       timer = setTimeout(function(){
           var inputValue = $("#mdssearch").val();
           table.column(0).search(inputValue, true, false).draw();     
   }, 500)
   });
}