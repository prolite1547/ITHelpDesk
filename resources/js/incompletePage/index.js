import {categoryADynamicCategoryBSelect} from "../global";

export const incompletePageController = () => {

    categoryADynamicCategoryBSelect();
    var group = $('#group')
    
    $.ajax('/get/group', {
        type: 'POST',
        data:  {
            'assign' : 'none'
        }
    })
    .done((data) => {
        group.val(data.id);
    })
    .fail(() => {
        console.log("failed to get user group")
    });
 
    
    $('#assigneeSelect').on('change',function(){
        var id = $(this).val();
        var assign = 'assigned';
        if(id == ''){
            assign = 'none';
        }

        $.ajax('/get/group', {
            type: 'POST',
            data:  {
                'assign' : assign,
                'id' : id
            }
        })
        .done((data) => {
            group.val(data.id);
        })
        .fail(() => {
            console.log("failed to get user group")
        });
    
    });

};
