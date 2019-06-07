import {
    clearLoader,
    elements, elementStrings,
    insertToModal,
    renderLoader,
    showModal
} from "../views/base";


export const devProjsController = () => {
   var table =  $('#devprojs-table').DataTable({
        dom: 'lrtip',
        processing: true,
        serverSide: true,
        order: [[3, 'desc']],
        "bPaginate": true,
        "sPaginationType": "full_numbers",
        ajax: '/get/devprojects',
        columns: [
            { data: 'project_name', name: 'project_name' },
            { data: 'assigned_to', name: 'assigned_to' },
            { data: 'status', name: 'status' },
            { data: 'date_start', name: 'date_start' },
            { data: 'date_end', name: 'date_end' },
            { data: 'md50_status', name: 'md50_status' },
            { data: 'md50_status', name: 'actions' }
        ],
         columnDefs: [
                { 
                  orderable: true, 
                  targets: [0,1,2,3]
                },
                {
                    targets: 6, /*ACTIONS*/
                    createdCell: ( cell, cellData,rowData) => {
                        // cell.setAttribute('title',rowData.subject);
                    },
                    orderable: false,
                    render: (data, type, row) => {
                       
                        return `<button data-action='edit' data-rid='${row.id}'   class='btn-edit btn btn--blue' title='Update Details'><i class='fa fa-pen'></i></button> 
                                <button data-action='delete' data-rid='${row.id}' class='btn-delete btn btn--red' title='Delete Report'><i class='fa fa-trash'></i></button>`
                    }

                 }
                 
         ]
    });

   $('#devprojs-table').on('click', '.btn-edit', (e)=>{ 
       e.preventDefault();
       var rID = $(e.currentTarget).data('rid');
       showModal();
       renderLoader(elements.modalContent);
       $.ajax({
           url : '/show/'+rID+'/editdevprojects',
           type: 'GET',
           data : {},
           success : function(data){
            clearLoader();
            insertToModal(data);
           }
       });
     
   });
 
 
   $('#devprojs-table').on('click', '.btn-delete', (e)=>{
       var rID = $(e.currentTarget).data('rid');
       var yesno = confirm("You are about to delete this project, Are you sure ?");
       if (yesno){
            $.ajax({
                url : '/delete/'+rID+'/devprojects',
                type: 'GET',
                data : {},
                success : function(data){
                    window.alert("Project successfully deleted !");
                    location.reload();
                }
            });
       }
   }); 
   

   var timer = null;

   $('#devsearch').on('keyup', function () {
       clearTimeout(timer); 
       timer = setTimeout(function(){
           var inputValue = $("#devsearch").val();
           table.column(0).search(inputValue, true, false).draw();     
   }, 500)
   });


   $('#status').on('change',function(){
        var status = $(this).val();
        if(status == 'Done'){
            $('#dateEnd').prop("required", true);
        }else{
            $('#dateEnd').removeAttr("required");
        }
   });


   $(".popup__content").on('change', '#status',function(){
         var status = $(this).val();
         if(status == 'Done'){
            $('#dateEnd').prop("required", true);
        }else{
            $('#dateEnd').removeAttr("required");
        }
   });
}