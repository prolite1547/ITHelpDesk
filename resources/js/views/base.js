export const elements = {
    table: document.querySelector('table'),
    select2elements: $('.form__input--select2'),
    editTicketButton: document.querySelector('.ticket-content__link--edit'),
    addTicketWindow: document.querySelector('.window__content'),
    ticketDetails: document.querySelector('p.ticket-content__details'),
    ticketSubject: document.querySelector('.ticket-content__subject'),
    updateButtonsContainer: document.querySelector('.ticket-content__updateBtns'),
    ticketContent: document.querySelector('.ticket-content'),
    ticketID: document.querySelector('.ticket-details__id'),
    modal: document.querySelector('.popup'),
    modalContent: document.querySelector('.popup__body'),
    container: document.querySelector('.container'),
    ticketDetailsEditIcon: document.querySelector('.ticket-details__icon--edit'),
    ticketDetailsAddFilesIcon: document.querySelector('.ticket-details__icon--add'),
    popupClose: document.querySelector('.popup__close'),
    chatSendButton: document.querySelector('.chat__button'),
    reply: document.querySelector('.chat__textarea'),
    categoryInput: document.querySelector('.form__input[name="category"]'),
    profilePicEditIcon: document.getElementById('profImage'),
    ticketAddSubmitBtn: document.getElementById('ticketAdd'),
    addCallerForm: document.getElementById('addCaller'),
    addBranchForm: document.getElementById('addBranch'),
    addPositionForm: document.getElementById('addPosition'),
    addContactForm: document.getElementById('addContact'),
    addDepartmentForm: document.getElementById('addDepartment'),
    addTicketForm: document.querySelector('#form-addTicket'),
    contactFormGroup: document.getElementById('contactFormGroup'),
    extendFormBtn: document.querySelector('.ticket-content__link--extend'),
    userCallerForm: document.querySelector('.fieldset__contact-inputs'),

    incidentFormItem: document.getElementById('incidentForm'),
    PLDTFormItem: document.getElementById('PLDTForm'),
    incidentFormContainerAdd: document.getElementById('incidentFormContainer'),
    PLDTFormContainerAdd: document.getElementById('PLDTFormContainer'),
    formItems: document.getElementsByClassName('window__item'),
    PLDTForm: document.querySelector('.form-email'),
    maintenanceCol: document.querySelector('.plusToggleContainer'),
    addTicketDetailsForm: document.querySelector('.form-addTicketDetails'),
    addTicketDetailsFormTicketEl: document.querySelector('.form-addTicketDetails__ticket-value'),
    btnShwExtndDtails: document.querySelector('.ticket-details__value--extend'),

    selectPID: document.querySelector('.form-email__input-select--pid'),
    selectTel: document.querySelector('.form-email__input-select--tel'),
    selectConcern: document.querySelector('.form-email__input-select--concern'),

    fixButtonShowDetails: document.querySelector('button[data-action=viewFixDtls'),
    chatForm: document.querySelector('.chat'),
    printTicketBtn: document.querySelector('.ticket-content__link--print'),
    ticketDetailStore: document.querySelector('a.ticket-details__value--store'),
    fixBtn: document.querySelector('.ticket-content__link--fix'),
    chatForm__reply: document.querySelector('.group[data-thread=reply]'),
    chatForm__chat: document.querySelector('.group[data-thread=chat]'),

    refreshBtn: document.querySelector('svg[data-refresh]'),


    filterTicketsIcon:document.querySelector('#ticketFilter'),
    filterContent: document.querySelector('.filter'),
    filterTicketForm: document.querySelector('.form-ticketFilter'),
    clearFilter: document.querySelector('#clearFilter'),
    rejectDetailsBtn: document.querySelector('button[data-action=viewRjctDtls]'),

    btnAddRelated: document.getElementById('btnAddRelated'),
    btnShowAppStats: document.getElementById('appStatus'),

    /*ADMIN PAGE*/
    addUserBtn: document.querySelector('[data-action=addUserBtn]'),

    /*Add Ticket Page */
    windowMaintenance: document.querySelector('.window__maintenance'),
    /* Maintenance Page El */
    plusToggleContainer: document.querySelector('.form-categoriesAdd'),

};

export const elementStrings = {
    ticketCheckbox: '.menu__checkbox',
    select2element: '.form__input--select2',
    loader2: 'loader2',
    ticketContentEditIcon: '.ticket-content__link--edit',
    fix_form: '.form-fix',
    addCallerSubmit: 'button[data-action=addCaller]',
    addBranchSubmit: 'button[data-action=addBranch]',
    addContactSubmit: 'button[data-action=addContact]',
    branchSelectContact: 'select[data-select=contact]',
    depSelectpos: 'select[data-select=position]',
    ticketAddBtn: '#ticketAdd',
    rejectBtnShowForm: '[data-action=showRejectForm]',
    resolveBtn: '[data-action=resolveTicket]',
    select2Elements: '.form__input--select2',


    /*TICKET ADD*/
    ticketAddFormActive: 'window__item--active',
    addPLDTIssueSubmit: 'button[data-action=addPLDTIssue]',

    /*ADMIN PAGE*/
    addUserFrom: 'addUser',

    /*Technical Store Visit*/
    targetTable:'#storeTarget',
    detailsTable:'#storeDetails',
};


export const renderLoader = parent => {
    const loader = `
        <div class="${elementStrings.loader2}">
            <svg>
                <use href="/images/loader/icons.svg#icon-cw"></use>
            </svg>
        </div>
    `;
    parent.insertAdjacentHTML('afterbegin', loader);
};

export const clearLoader = () => {
    const loader = document.querySelector(`.${elementStrings.loader2}`);
    if (loader) loader.parentElement.removeChild(loader);
};

export const showModal= (markup = false,close = true) => {
    const popup__close = elements.modal.querySelector('.popup__close');

    close === true ? popup__close.classList.remove('u-display-n') : popup__close.classList.add('u-display-n');

    if(markup){
        elements.modalContent.innerHTML = markup;
    }else{
        elements.modalContent.innerHTML = "";
    }
    elements.container.style.filter = 'blur(1px)';
    elements.modal.style.visibility = 'visible';

    elements.modal.style.opacity = '1';

};

export const insertToModal = (markup) => {
    elements.modalContent.insertAdjacentHTML('beforeend',markup);
};

export const hideModal= () => {
    elements.container.style.filter = '';
    elements.modal.style.visibility = 'hidden';
    elements.modal.style.opacity = '0';
    elements.modalContent.innerHTML = "";
};

export const displayError = (jqXHR) => {
    let errorMessage = '';
    Object.entries(jqXHR.responseJSON.errors).forEach(([key, value]) => errorMessage+=value);
    alert(errorMessage);
};

export const setDisable = (el,bool = true) => {
      el.disabled = bool;
};

export const clearFormInputs = (form) => {
    form.reset();
};


export const toggleFormGroups = (e) => { /*FOR ELEMENTS THAT HAVE + ICON AND HIDDEN FORM GROUP*/
        if(e.target.matches('button')){
            e.target.firstElementChild.classList.toggle('fa-plus');
            e.target.firstElementChild.classList.toggle('fa-minus');
            e.target.nextElementSibling.classList.toggle('u-display-n');

        }else if(e.target.matches('i')){
            e.target.parentNode.nextElementSibling.classList.toggle('u-display-n');
            e.target.classList.toggle('fa-plus');
            e.target.classList.toggle('fa-minus');
        }
};


