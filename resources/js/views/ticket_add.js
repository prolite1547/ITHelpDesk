import {elements, elementStrings} from "./base";
import * as globalFunc from "./../global";

export const generateExpirationInputMarkup = (category) => {

    let date;
    switch (category) {
        case 'hardware':
            date = moment().add(3, 'days').format('YYYY-MM-DD HH:mm:ss');
            break;
        case 'software':
            date = moment().add(7, 'days').format('YYYY-MM-DD HH:mm:ss');
            break;
        default:
            date = moment().format();
    }

    return `<input name="expiration" value="${date}" hidden>`;
};


export const showContactFormGroup = () => {
    elements.contactFormGroup.classList.remove('u-display-n');

};

export const hideContactFormGroup = () => {
    elements.contactFormGroup.classList.add('u-display-n');
};

export const displayForm = () => {
    let items;
    items = elements.formItems;

    for (let i = 0; i < items.length; i++) {
        if ($(items[i]).hasClass('window__item--active')) {
            showForm(items[i].id);
        }
    }
};

function showForm(id) {

    if (id === 'incidentForm') {
        elements.PLDTFormContainerAdd.style.display = 'none';
        elements.incidentFormContainerAdd.style.display = 'block';
    } else if (id === 'PLDTForm') {
        elements.incidentFormContainerAdd.style.display = 'none';
        elements.PLDTFormContainerAdd.style.display = 'block';
    } else {
        alert('Form Not Found!');
    }
}


export const showUserForm = (show) => {

    const inputElements = elements.userCallerForm.querySelectorAll('input,select');

    if (show === true) {
        elements.userCallerForm.classList.remove('u-display-n');
            globalFunc.disableInputElements(inputElements,false);
    } else {
        elements.userCallerForm.classList.add('u-display-n');
            globalFunc.disableInputElements(inputElements,true);
    }

};


export const genUserGroup = () => {
    var group = $('#group');
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
}

