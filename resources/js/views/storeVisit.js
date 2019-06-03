import {clearLoader, elements, elementStrings, insertToModal, renderLoader, showModal} from "./base";
import {branchSelect2, technicalUserSelect2} from "../select2";
import {sendForm} from "../global";
import {store_visit_details, store_visit_target} from "./datatablesOptions";


$("#storeTarget").DataTable(store_visit_target);
$("#storeDetails").DataTable(store_visit_details);


export const fetchForm = (action) => {
    beforeShowModal();
    $.ajax(addUrl(action),{
        type: 'GET'
    }).done((data) => {
        showModal(data);
        initSelect2();
        modalFormSubmitListener();

    }).fail(() => {alert('Fail to get target form!');clearLoader();});
};

export const editModal = (action,id) => {
    beforeShowModal();
    $.ajax(editUrl(action,id),{
        type: 'GET'
    }).fail(() => alert('Failed to get details!'))
        .done((data) => {
            showModal(data);
            initSelect2();
        });

};

export const getDataset = (el) => {
    let use_el;
    if(el.matches('svg')){
        use_el = el.firstChild;
    }else{
        use_el = el;
    }
    return [use_el.dataset.action,use_el.dataset.id];

};

export const deleteItem = (id,action) => {

    $.ajax(`${generateDeleteUrl(action)}delete/${id}`,{
        type: 'DELETE'
    }).fail(() => alert('Failed to delete item'))
        .done((data) => {
            alert('Successfully deleted item');
            reloadTable(data.table);
        });
};

export const reloadTable = (table) => {
    $(elementStrings[table]).DataTable().ajax.reload();
};

function generateDeleteUrl(action){
    return action === 'deleteStoreVisitTarget'? '/store-visit/target/' : '/store-visit/details/';
}

function  addUrl(action) {
    let url = '';

    if(action === 'addDetails'){
        url = '/modal/form/visit-details';
    }else if(action === 'addTarget'){
        url = '/modal/form/target';
    }else {
        return alert('form not found!');
    }

    return url;
}

function  editUrl(action,id) {
    let url = '';
    switch (action) {
        case 'editStoreVisitTarget':
            url = `/modal/form/editVisitTarget/${id}`;
            break;
        case 'editStoreVisitDetails':
            url = `/modal/form/editVisitDetails/${id}`;
            break;
        default:
            return 'edit form not found';
    }

    return url;
}

function beforeShowModal(){
    showModal(false);
    renderLoader(elements.modalContent);
}

function initSelect2() {
    $('.branchSelect2').select2(branchSelect2);
    $('.techUsersSelect2').select2(technicalUserSelect2);
}

function modalFormSubmitListener() {
    elements.modalContent.querySelector('form').addEventListener('submit',sendForm);
}

