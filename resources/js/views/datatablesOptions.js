const columnStrings = {
    category:'',
    status_name:'status_name',
    store_name:'store_name',
    created_at:'',
    expiration: '',
    resolved_by: 'resolver',
    logged_by: 'logger',
    assignee: 'assigned_user',
    priority: 'priority_name'
};


const userTickets = {
    ajax: '/tickets/ticket-data/my',
    order: [[5, 'desc']],
    columns: [
        {data: null,name: 'id'},
        {data: 'category', visible: false},
        {data: columnStrings.priority},
        {data: columnStrings.status_name,orderable: false},
        {data: 'store_name',name:columnStrings.store_name},
        {data: 'created_at',visible: true},
        {data: 'expiration'},
        {data: 'id', orderable: false}
    ],

};


const openTickets = {
    ajax: '/tickets/ticket-data/open',
    order: [[5, 'desc']],
    columns: [
        {data: null,name: 'id'},
        {data: 'category', visible: false,defaultContent: 'Not Set'},
        {data: columnStrings.priority,defaultContent: 'Not Set'},
        {data: columnStrings.status_name, orderable: false,defaultContent: 'Not Set'},
        {data: 'store_name',name:columnStrings.store_name},
        {data: 'created_at',visible: true},
        {data: 'expiration',defaultContent: 'Not Set'},
        {data: columnStrings.logged_by},
        {data: 'id', orderable: false}
    ],

};

const expiredTickets = {
    ajax: '/tickets/ticket-data/expired',
    order: [[4, 'desc']],
    columns: [
        {data: null,name: 'id'},
        {data: 'category', visible: false,name:'category'},
        {data: columnStrings.priority},
        {data: columnStrings.status_name,orderable: false},
        {data: 'store_name',name:columnStrings.store_name},
        {data: 'created_at',visible: true},
        {data: 'expiration'},
        {data: columnStrings.assignee,visible: true},
        {data: 'id', orderable: false}
    ],
};

const ongoingTickets = {
    ajax: '/tickets/ticket-data/ongoing',
    order: [[5, 'desc']],
    columns: [
        {data: null,name: 'id'},
        {data: 'category', visible: false},
        {data: columnStrings.priority},
        {data: columnStrings.status_name,orderable: false},
        {data: 'store_name',name:columnStrings.store_name},
        {data: 'created_at',visible: true},
        {data: 'expiration'},
        {data: columnStrings.assignee,visible: true},
        {data: 'id', orderable: false}
    ],
};

const closedTickets = {
    ajax: '/tickets/ticket-data/closed',
    order: [[7, 'desc']],
    columns: [
        {data: null,name: 'id'},
        {data: 'category',visible: false},
        {data: columnStrings.priority},
        {data: columnStrings.status_name,orderable: false},
        {data: 'store_name',name:columnStrings.store_name},
        {data: 'created_at',visible: true},
        {data: 'expiration',visible: false},
        {data: 'resolved_date'},
        {data: columnStrings.assignee,visible: true},
        {data: columnStrings.resolved_by,visible: true},
        {data: 'id', orderable: false}
    ],
};

const fixedTickets = {
    ajax: '/tickets/ticket-data/fixed',
    order: [[5, 'desc']],
    columns: [
        {data: null,name: 'id'},
        {data: 'category', visible: false},
        {data: columnStrings.priority},
        {data: columnStrings.status_name,orderable: false},
        {data: 'store_name',name:columnStrings.store_name},
        {data: 'created_at',visible: true},
        {data: 'expiration',visible: false},
        {data: 'fix_date'},
        {data: 'fixed_by',visible: true},
        {data: 'id', orderable: false} 
    ],
};

const allTickets = {
    ajax: '/tickets/ticket-data/all',
    order: [[5, 'desc']],
    columns: [
        {data: null,name: 'id'},
        {data: 'category', visible: false},
        {data: columnStrings.priority},
        {data: columnStrings.status_name,orderable: false},
        {data: 'store_name',name:columnStrings.store_name},
        {data: 'created_at',visible: true},
        {data: 'expiration'},
        {data: columnStrings.assignee,visible: true},
        {data: 'id', orderable: false}
    ]
};

export const store_visit_target = {
    ajax: '/store-visit/targets',
    order: [[3, 'desc']],
    columns: [
        {data:
            (row) => {
                return moment().month(row.month - 1).format("MMMM");
            }
        },
        {data: 'year'},
        {data: 'num_of_stores'},
        {data:
                (row) => {
                    return moment(row.created_at).format("MMM DD, YYYY");
                }
        },
        {
            data: 'id',
            render:(data) => {
                return `<svg class="sprite sprite--blue storeVisit__edit"><use xlink:href="/svg/sprite2.svg#icon-edit" data-action="editStoreVisitTarget" data-id="${data}"></use></svg>
                <svg class="sprite sprite--red storeVisit__delete"><use xlink:href="/svg/sprite2.svg#icon-trash" data-action="deleteStoreVisitTarget" data-id="${data}"></use></svg>`;
            }}
    ]
};

export const store_visit_details = {
    ajax: '/store-visit/details',
    order: [[5, 'desc']],
    columns:
    [
        {data:'store.store_name'},
        {data: 'full_name'},
        {data: 'status.name'},
        {data:'start_date'},
        {data:'end_date'},
        {data:'created_at'},
        {
            data: 'id',
            render: (data) => {
                return `<svg class="sprite sprite--blue storeVisit__edit"><use xlink:href="/svg/sprite2.svg#icon-edit" data-action="editStoreVisitDetails" data-id="${data}"></use></svg>
                <svg class="sprite sprite--red storeVisit__delete"><use xlink:href="/svg/sprite2.svg#icon-trash" data-action="deleteStoreVisitDetails" data-id="${data}"></use></svg>`;
            }
        }
    ]
};

export const posTickets = {
    ajax: '/tickets/ticket-data/pos',
    order: [[5, 'desc']],
    columns: [
        {
            data: null,
            name: 'id',
            createdCell: ( cell, cellData,rowData) => {
                cell.setAttribute('title',rowData.subject);
            },
            orderable: false,
            render: (data, type, row) => {
                return `<a href='javascript:void(0)' class='table__subject' data-action="ticketDtlsModal" data-id="${data.id}">${row.subject}</a>
                <span class='table__info'>Ticket #: ${data.id}</span>
                <span class='table__info'>POS Category: <strong>${row.catB_name}</strong></span>
                <!--<span class='table__info'>Group: ${data.ticket_group}</span>-->
                ${(data.extend_count > 0) ? `<span class='table__info table__info--red'>Extended (${data.extend_count})</span>` : ''}`
            }
        },
        {data: 'category', visible: false},
        {data: columnStrings.priority},
        {data: columnStrings.status_name,orderable: false},
        {data: 'store_name',name:columnStrings.store_name},
        {
            data: 'created_at',
            visible: true,
            createdCell: ( cell, cellData) => {
                cell.innerText = moment(cellData).format('MMMM DD,YYYY');
            }
        },
        {data: columnStrings.logged_by,visible: true},
        {data: columnStrings.assignee,visible: true},
        {
            data: 'id',
            orderable: false,
            createdCell: ( cell, cellData) => {
                cell.innerHTML = `<a href="/print/${cellData}/ticket" target="_blank" class="btn"><svg class="sprite"><use xlink:href="/svg/sprite2.svg#icon-print"></use></svg></a>`;
            }
        }
    ]
};






export const initDataTables = () => {

    let options, ticketStatus;

    /*REGEX STRING FOR TICKET ROUTES TO CATURE THE TICKET STATUS*/
    const ticketRegex = new RegExp('\/tickets\/([a-z]+)', 'gm');

    /*GET TICKET STATUS FROM THE URL*/
    ticketStatus = ticketRegex.exec(window.location.pathname)[1];


    /*IF STATEMENT FOR THE RIGHT OPTION TO BE INITIALIZED TO DATATABLE*/
    if (ticketStatus === 'open') {
        options = openTickets;
    } else if (ticketStatus === 'all') {
        options = allTickets;
    } else if (ticketStatus === 'my') {
        options = userTickets;
    } else if (ticketStatus === 'ongoing') {
        options = ongoingTickets;
    } else if (ticketStatus === 'closed') {
        options = closedTickets;
    }  else if (ticketStatus === 'fixed') {
        options = fixedTickets;
    }else if (ticketStatus === 'expired') {
        options = expiredTickets;
    }else {
        console.log('ticket status not found');
    }


    /*APPLY THE GENERATED DATATABLE OPTION*/
    return $('table').DataTable(options);



};
