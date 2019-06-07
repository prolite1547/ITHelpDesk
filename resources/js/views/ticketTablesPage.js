import {clearFormInputs, elements, elementStrings} from "./base";
import * as datatableOption from "./datatablesOptions";

export const checkBoxListen = () => {
    if(elements.table) {
        elements.table.addEventListener('click',e => {
            if(e.target.matches(elementStrings.ticketCheckbox)){

                //clear menu


                //show the menu
                e.target.closest('tr').classList.toggle('selected-row');
                console.log(e.target.parentNode.childNodes['1'].classList.toggle('u-display-n'));
            }
        });
    }
};


export const setDataTablesConf = () => {
    $.extend( true, $.fn.dataTable.defaults, {
        autoWidth: false,
        searching: true,
        columnDefs: [
            {
                targets: -1, /*CHECKBOX*/
                render: () => {
                    return `<div class='menu'>
                            <ul class='menu__list u-display-n'>
                                <li class='menu__item'><a href='javascript:void(0);' class='menu__link'>Print</a></li>
                                <li class='menu__item'><a href='javascript:void(0);' class='menu__link'>Delete</a></li>
                                <li class='menu__item'><a href='javascript:void(0);' class='menu__link'>Mark as resolved</a></li>
                           </ul>
                            <input type='checkbox' class='menu__checkbox'>
                        </div>`
                }
            },
            {
                targets: 2, /*CATEGORY*/
                defaultContent:'Not Set',
                render: (data) => {
                    if(data) return `<span class='u-bold u-${data.toLowerCase()}'>${data}</span>`;

                }
            },
            {
                targets: 0, /*SUBJECT*/
                defaultContent:'Not Set',
                createdCell: ( cell, cellData,rowData) => {
                    cell.setAttribute('title',rowData.subject);
                },
                orderable: false,
                render: (data, type, row) => {
                    return `<a href='/tickets/view/${data.id}' class='table__subject'>${row.subject}</a>
                <span class='table__info'>Ticket #: ${data.id}</span>
                <span class='table__info'>Category: ${row.category}</span>
                <span class='table__info'>Group: ${data.ticket_group_name}</span>
                ${(data.times_extended > 0) ? `<span class='table__info table__info--red'>Extended (${data.times_extended})</span>` : ''}`
                }
            },
            {
                targets: 5, /*CREATED_AT COLUMN*/
                createdCell: ( cell, cellData) => {
                    cell.setAttribute('title',cellData);
                },
                render: (data) => {
                    return moment(data).fromNow();
                }

            },
            {
                targets: 6, /*EXPIRATION DATE COLUMN*/
                defaultContent:'Not Set',
                createdCell: ( cell, cellData) => {
                    cell.setAttribute('title',cellData);
                },
                render: (data,type,row) => {

                    if(data){
                        let now;
                        now = moment();
                        if(row.status == 'Expired'){
                            return data;
                        }else{
                            if(now >= moment(data)){
                                return `<span class="expired">Expired</span>`;
                            }else {
                                return moment(data).fromNow();
                            }
                        }

                    }

                }

            }
        ],
        dom: 'lrtip',
        processing: true,
        serverSide: true,
        orderable: false,
        select:true,
        iDisplayLength: 6,
        aLengthMenu: [[6,10, 25, 50, -1], [6,10, 25, 50, "All"]],
        language: {
            processing: '<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>'
        }
    } );


    $.extend( $.fn.dataTable.ext.classes, {
        "sTable": "",
        "sNoFooter": "no-footer",

        /* Paging buttons */
        "sPageButton": "paginate_button",
        "sPageButtonActive": "current",
        "sPageButtonDisabled": "disabled",

        /* Striping classes */
        "sStripeOdd": "odd",
        "sStripeEven": "even",

        /* Empty row */
        "sRowEmpty": "dataTables_empty",

        /* Features */
        "sWrapper": "dataTables_wrapper",
        "sFilter": "dataTables_filter",
        "sInfo": "dataTables_info",
        "sPaging": "dataTables_paginate paging_", /* Note that the type is postfixed */
        "sLength": "dataTables_length",
        "sProcessing": "dataTables_processing",

        /* Sorting */
        "sSortAsc": "sorting_asc",
        "sSortDesc": "sorting_desc",
        "sSortable": "sorting", /* Sortable in both directions */
        "sSortableAsc": "sorting_asc_disabled",
        "sSortableDesc": "sorting_desc_disabled",
        "sSortableNone": "sorting_disabled",
        "sSortColumn": "sorting_", /* Note that an int is postfixed for the sorting order */

        /* Filtering */
        "sFilterInput": "",

        /* Page length */
        "sLengthSelect": "",

        /* Scrolling */
        "sScrollWrapper": "dataTables_scroll",
        "sScrollHead": "dataTables_scrollHead",
        "sScrollHeadInner": "dataTables_scrollHeadInner",
        "sScrollBody": "dataTables_scrollBody",
        "sScrollFoot": "dataTables_scrollFoot",
        "sScrollFootInner": "dataTables_scrollFootInner",

        /* Misc */
        "sHeaderTH": "",
        "sFooterTH": "",

        // Deprecated
        "sSortJUIAsc": "",
        "sSortJUIDesc": "",
        "sSortJUI": "",
        "sSortJUIAscAllowed": "",
        "sSortJUIDescAllowed": "",
        "sSortJUIWrapper": "",
        "sSortIcon": "",
        "sJUIHeader": "",
        "sJUIFooter": ""
    } );
};

export const filterFunction = () => {
    let table;

    /*GET DATATABLE OPTION FOR INITIALIZATION options*/
    table = datatableOption.initDataTables();

    elements.filterTicketsIcon.addEventListener('click', () => {
        elements.filterContent.classList.toggle('u-display-n');
    });

    elements.clearFilter.addEventListener('click',() => {
        clearFormInputs(elements.filterTicketForm);
        table.columns().search('').draw();

    });

    elements.filterTicketForm.addEventListener('submit', (e) => {

        /*CHECK FORM THROUGH HTML 5 VALIDATION */
        if(e.target.checkValidity()){
            e.preventDefault();
            let inputs = $(e.target).serializeArray();

            inputs.forEach((currentValue) => {
                table.column(currentValue['name']).search(currentValue['value'],true).draw();
            });
        }
    });
};
    export const myElements = {
        radios :  document.querySelectorAll('.form-ticketFilter__radio'),
        userLabel:  document.querySelector('.form-ticketFilter__label--user'),
        userSelectInput: document.querySelector('.form-ticketFilter__input--user')
    };
