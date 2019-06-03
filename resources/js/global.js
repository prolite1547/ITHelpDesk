import {setDisable, displayError, showModal, renderLoader, elements, clearLoader, hideModal} from "./views/base";
import Caller from './models/Caller';
import Store from './models/Store';
import Contact from './models/Contact';
import ConnectionIssue from "./models/ConnectionIssue";
import Position from "./models/Position";
import Department from "./models/Department";
import StoreVisitTarget from "./models/StoreVisitTarget";
import StoreVisitDetail from "./models/StoreVisitDetail";
import {reloadTable} from "./views/storeVisit";


export const sendForm = (e) => {
    if(e.target.checkValidity()){
        e.preventDefault();

        let formdata,form,object,formSbmtBtn;
        form = e.target;
        formSbmtBtn = form.querySelector('button[data-action]');

        setDisable(formSbmtBtn);

        /*SERIALIZE FORM DATA*/
        formdata = $(form).serialize();

        switch (form.id) {
            case 'addCaller':
                object =  new Caller();
                break;
            case 'addBranch':
                object = new Store();
                break;
            case 'addContact':
                object = new Contact();
                break;
            case 'addPLDTIssue':
                showModal(false,false);
                renderLoader(elements.modalContent);
                object = new ConnectionIssue();
                formdata = new FormData(form);
                break;
            case 'addPosition':
                object = new Position();
                break;
            case 'addDepartment':
                object = new Department();
                break;
            case 'addStoreVisitTarget':
                object = new StoreVisitTarget();
                break;
            case 'addStoreVisitDetail':
                object = new StoreVisitDetail();
                break;
            default:
                alert('form not found');
        }

        if(object){
            object.storeData(formdata)
                .done((data) => {
                        alert('Added Successfully!!');
                        clearLoader();
                        hideModal();
                        form.reset();
                        setDisable(formSbmtBtn,false);
                        responseHandler(data);
                })
                .fail((jqXHR,textStatus,errorThrown) => {
                        clearLoader();
                        hideModal();
                        setDisable(formSbmtBtn,false);
                        if(jqXHR){
                            displayError(jqXHR)
                        }else if(errorThrown) {
                            alert(errorThrown);
                        }else if(textStatus){
                            alert(textStatus);
                        }else{
                            alert('Unable to process the request!')
                        }
                });
        }
    }

};

export const addData = (url,data) => {

    return $.ajax(url,{
        type: 'POST',
        data: data
    });

};

export const toggleHiddenGroup = (e) => {
    let data,form,hiddenGroup;
    data = e.params.data;
    form = e.delegateTarget.form;
    hiddenGroup = form.querySelector('.u-display-n');
    if(data.id !== ""){
        // addTicketView.showContactFormGroup();
        hiddenGroup.classList.toggle('u-display-n');
    }else{
        // addTicketView.hideContactFormGroup();
    }
};


export const disableSubmitBtn = (btn,text = '',disable = true) => {

    if(disable === true){
        btn.value = 'Please Wait...';
        btn.disabled = true;
    }else{
        btn.value = text;
        btn.disabled = false;
    }

};


export const disableInputElements = (nodeList,disable) => {
    for (let input of nodeList) input.disabled = disable;
};


function responseHandler(data){
    switch (data.response) {
        case 'emailConIssueSentSuccess':
            window.location = `/tickets/view/${data.data.ticket_id}`;
        break;
        case 'storeVisitTarget':
            data.success === true ? reloadTable('targetTable') : false;
        break;
        case 'storeVisitDetails':
            data.success === true ? reloadTable('detailsTable') : false;
            break;
        default:
            alert('response not found!');
    }
}
