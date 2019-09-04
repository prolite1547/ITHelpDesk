import {elements, elementStrings, displayError} from "./views/base";
import * as editTicketView from './views/editIicketView';
import * as addTicketView from './views/ticket_add';
import Ticket from './models/Ticket';
import Message from './models/Message';
import Fix from './models/Fix';
import {branchSelect2, cntctSelect2, deptSelect2, psitionSelect2,userSelect2} from "./select2";
import * as glboalScript from './global';
import {renderLoader, clearLoader, showModal, insertToModal, hideModal} from "./views/base";
import ConnectionIssueReply from "./models/ConnectionIssueReply";
import {disableSubmitBtn} from "./global";
import {categoryADynamicCategoryBSelect} from "./global";


////////////////////////////////
////////////////////////////////
////*ADD TICKET CONTROLLER*/////
////////////////////////////////
////////////////////////////////

const emailSelect2Options = {
    placeholder: "To",
    width: '100%',
    tags: true,
    tokenSeparators: [',', ' ']
};

export const ticketAddController = () => {
    /*INITIALIZE*/
    (function () {
        /*ADD THE ACTIVE CLASS TO THE INCIDENT ITEM*/
        // elements.incidentFormItem.classList.add(elementStrings.ticketAddFormActive);
        elements.incidentFormItem.classList.add(elementStrings.ticketAddFormActive);

        /*DISPLAY THE FORM*/
        addTicketView.displayForm();
        addTicketView.genUserGroup();
    })();

    categoryADynamicCategoryBSelect();

    $('#emailTo').select2(emailSelect2Options);

    $('#emailCc').select2(emailSelect2Options);

    $('#callerBranchSelect,.branchSelect').select2(branchSelect2);
    $('#ticketPositionSelect').select2(psitionSelect2);
    $('#positionDepSelect').select2(deptSelect2);
    $('#contact_id').select2(cntctSelect2);
    $('#assigneeSelect').select2();

    const userSelect = $('.userSelect');
    if(userSelect) {
        userSelect.select2(userSelect2);

        userSelect.on('change',() => {

            if(parseInt(userSelect.select2('data')[0].id) === 0) {
                addTicketView.showUserForm(true);
            }else {
                addTicketView.showUserForm(false);
            }
        });
    }


    /*DYNAMIC FORM*/
    document.querySelector('.window').addEventListener('click', e => {
        if (e.target.matches('.window__item')) {
            let items, item;
            items = e.target.parentNode.querySelectorAll('.window__item');
            item = e.target;

            /*REMOVE THE ACTIVE CLASS FROM THE ITEMS*/
            items.forEach(el => {
                el.classList.remove(elementStrings.ticketAddFormActive);
            });

            /*ADD THE ACTIVE CLASS TO THE CLICKED ITEM*/
            item.classList.add(elementStrings.ticketAddFormActive);

            /*DISPLAY THE FORM*/
            addTicketView.displayForm();
        }
    });

    /*ADD EVENT LISTENER ON ADD TICKET FORM*/
    elements.addTicketForm.addEventListener('submit', (e) => {
        if (e.target.checkValidity()) {
            e.preventDefault();
            e.target.classList.toggle('u-display-n');
            renderLoader(e.target.parentElement);

            $.ajax('/ticket/add', {
                type: 'POST',
                data: $(e.target).serialize()
            })
                .done((data) => {
                    elements.addTicketDetailsFormTicketEl.value = data.ticket_id;
                    elements.addTicketDetailsForm.classList.toggle('u-display-n');
                    clearLoader();
                })
                .fail(() => {
                    alert('Fail To Add Ticket;');
                    clearLoader();
                });
        }
    });


    $(elementStrings.depSelectpos).on('select2:select', glboalScript.toggleHiddenGroup);


    // elements.addCallerForm.addEventListener('submit', sendForm.bind(this,elementStrings.addCallerSubmit));
    
    if(elements.windowMaintenance.childElementCount !== 0){
        elements.addPositionForm.addEventListener('submit', glboalScript.sendForm);
        elements.addDepartmentForm.addEventListener('submit', glboalScript.sendForm);
        elements.addBranchForm.addEventListener('submit', glboalScript.sendForm);
    }
    
    elements.PLDTForm.addEventListener('submit', glboalScript.sendForm);


    /*get concern element*/
    elements.selectConcern.addEventListener('change', editTicketView.showSelects);
};

////////////////////////////////
////////////////////////////////
////*LOOK UP TICKET CONTROLLER*/////
////////////////////////////////
////////////////////////////////

export const ticketViewController = () => {
    const ticket = new Ticket(elements.ticketID, elements.ticketSubject, elements.ticketDetails);

    Echo.private(`chat.${ticket.ID}`)
        .listen('MessageSent', (e) => {
            document.querySelector('.thread').insertAdjacentHTML('afterbegin', editTicketView.getMessageMarkup(e));
        });

    ticket.fetchOriginalData()
        .done(data => {
            /*TICKET STATUS IS EQUAL TO FIX(4)*/
            if (data.status == 4 || data.status == 3) {
                document.querySelector('button[data-action=viewFixDtls').addEventListener('click', editTicketView.getModalWithData.bind(null, data));
            } else {

                /*ADD CLICK EVENT LISTENER */
                elements.ticketContent.addEventListener('click', e => {
                    /*IF USER CLICK THE EDIT INSIDE THE MORE*/
                    if (e.target.matches(elementStrings.ticketContentEditIcon)) {
                        /*make elements editable*/
                        editTicketView.makeElementsEditable();

                        /*show save button*/
                        editTicketView.showButtons();
                    }


                    /*IF USER CLICK THE BUTTONS CANCEL OR DONE*/
                    if (e.target.matches('#contentEditSave')) {

                        /*PLACE DATA TO THE TICKET OBJECT*/
                        ticket.storeContentEditTicket(elements.ticketSubject, elements.ticketDetails);

                        /*XHR TO SAVE EDITED INPUTS*/
                        ticket.saveEdit(ticket.detailsEditData).done(data => {
                            if (data.success === true) {
                                editTicketView.makeElementsNotEditable();
                                editTicketView.hideButtons();
                                alert('Updated Successfully!');
                            } else {
                                alert('Failed to update...');
                            }
                        }).fail((jqXHR) => {
                            displayError(jqXHR);
                        });
                    }

                    if (e.target.matches('#contentEditCancel')) {
                        /*GET LATEST DETAILS OF THE TICKET*/
                        ticket.fetchOriginalData().done(() => {
                            editTicketView.restoreElementsTextContent(ticket.originalData);
                            /*RESTORE ORIGINAL INPUT VALUES*/
                        });
                        editTicketView.makeElementsNotEditable();
                        /*REMOVE THE EDITABLE MODE*/
                        editTicketView.hideButtons();
                        /*HIDE THE CANCEL AND DONE BUTTONS*/
                    }
                });

                /*EVENT LISTENER EDIT ICON CLICK*/
                if (elements.ticketDetailsEditIcon) {
                    elements.ticketDetailsEditIcon.addEventListener('click', () => {
                        ticket.createObjectForEditData(); /*CLEAR EDIT DATA*/

                        showModal(); /*SHOW MODAL*/

                        renderLoader(elements.modalContent);  /*RENDER LOADER*`/

                        /*GET THE MARKUP FOR THE MODAL*/
                        ticket.getEditModal()
                            .done(data => {
                                clearLoader();
                                insertToModal(data);
                                editTicketView.getAutoGroup();
                                editTicketView.addEventListenerToEditInputs(ticket);
                                
                            })
                            .fail(error => {
                                console.log(`Error on making edit modal markup!! Error: ${error}`);
                            });
                    });
                    if (elements.ticketDetailsAddFilesIcon) {
                        elements.ticketDetailsAddFilesIcon.addEventListener('click', () => {
                            showModal();
                            /*SHOW MODAL*/
                            insertToModal(editTicketView.addFileMarkup);

                            const myDropzone = new Dropzone("#addFiles", {
                                url: `/file/ticket/${ticket.ID}`,
                                parallelUploads: 3,
                                uploadMultiple: true,
                                autoProcessQueue: false,
                                addRemoveLinks: true,
                                dictDefaultMessage: 'Drop files here to be uploaded'
                            });

                            myDropzone.on("complete", function () {
                                myDropzone.removeAllFiles();
                            });

                            document.querySelector('.dropzone__upload').addEventListener('click', () => {
                                if (myDropzone.files.length !== 0) {
                                    myDropzone.processQueue();
                                } else {
                                    return alert('No files found to be uploaded!!');
                                }
                            })

                        });
                    }
                    /*EVENT LISTENER ON CANCEL AND DONE BUTTON INSIDE TICKET DETAILS MODAL*/
                    elements.modal.addEventListener('click', e => {
                        if (e.target.matches('button')) {
                            const action = e.target.dataset.action;
                            if (action === 'cancel') {
                                hideModal();
                            } else if (action === 'confirm') {
                                ticket.detailsEditData['ticket']['group'] = $('#group').val();
                                ticket.saveEdit(ticket.detailsEditData).done(data => {
                                    if (data.success === true) {
                                        alert('Updated Successfully!');
                                        window.location.reload();
                                    } else {
                                        alert('Failed to update...');
                                    }
                                }).fail(data => {
                                    alert('tae');
                                });

                            }
                        } else if (e.target.matches('.capsule__close')) {

                            const capsule = e.target.closest('.capsule');

                            const removedFile = capsule.parentNode.removeChild(capsule);

                            const fileID = parseInt(removedFile.dataset.id);

                            ticket.storeToBeDeletedFileID(fileID);
                        }
                    });
                }


                if (elements.fixBtn) {
                    /*CLICK EVENT LISTENER ON RESOLVE BUTTON*/
                    elements.fixBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        showModal();
                        renderLoader(elements.modalContent);

                        editTicketView.getFixFormMarkUp()
                        .done(data => {
                            clearLoader();
                            insertToModal(data);


                            document.querySelector(elementStrings.fix_form).addEventListener('submit', e => {
                                e.preventDefault();
                                const formdata = $(e.target).serialize();
                                const fix = new Fix(ticket.ID, formdata);
                                fix.createFix()
                                    .done(() => {
                                        alert('Ticket marked as fixed successfully!!');
                                        window.location.reload();
                                        hideModal();
                                    })
                                    .fail((jqXHR) => {
                                        displayError(jqXHR);
                                    });
                            });

                        });
                    });


                }




            }


            // /*EVENT LISTENER ON SEND BUTTON*/
            if (elements.chatForm) {

                const incident_type = data.issue.incident_type;
                /*generate input depending if its mail or call*/
                if(incident_type === "App\\ConnectionIssue"){
                    editTicketView.generateForm('reply');
                }else if(incident_type === "App\\Incident"){
                    editTicketView.generateForm('chat');
                }

                /*generate inputs whenever user clicks that chat or reply menu*/
                elements.chatForm.addEventListener('click',e => {
                    if(e.target.matches('li')){
                        const form = e.target.dataset.form;
                        editTicketView.generateForm(form);
                    }
                });

                /*refreshes the thread of replies*/
                elements.refreshBtn.addEventListener('click',(e) => {
                    const threadEl = e.target.parentElement;
                    const refreshBtn = e.target;
                    threadEl.innerHTML = "";
                    renderLoader(threadEl);
                    ticket.getReplies()
                        .done(data => {
                            const replies = editTicketView.generateRepliesMarkup(data.data);
                            threadEl.innerHTML = replies;
                            threadEl.insertAdjacentElement('afterbegin',refreshBtn);
                        }).fail(() => {
                            alert('Failed to get replies!');
                    });
                });

                elements.chatForm.addEventListener('submit', e => {
                    if (e.target.checkValidity()) {
                        e.preventDefault();
                        let promise,error;
                        const action = e.target.dataset.form;
                        const form_elements = e.target.elements;
                        const reply = form_elements.reply.value;
                        const submitBtn = form_elements.chat__button;
                        const original_text = submitBtn.value;
                        disableSubmitBtn(submitBtn);

                        if(action === 'chat'){
                            const message = new Message;
                            promise = message.saveMessage(ticket.ID,reply);

                        }else if(action === 'reply'){
                            let subject;
                            const formData = new FormData(e.target);

                            subject = `Re: ${data.issue.subject} (TID#${data.id})`;

                            formData.append('subject',subject);

                            promise = ConnectionIssueReply.sendMailReply(formData);
                        }else{
                            error = 'Error on sending data!'
                        }

                        if(!error){
                            promise.done(() => {
                                alert('Message Sent Successfully!');
                                disableSubmitBtn(submitBtn,original_text,false);
                                e.target.reset();
                            })
                            .fail((jqXHR) => {
                                disableSubmitBtn(submitBtn,original_text,false);
                                displayError(jqXHR);
                            });
                        }
                    }
                });
            }

        });


    /*DELETE MESSAGE*/
    document.querySelector('.thread').addEventListener('click', e => {

        if (e.target.matches('.message__close-icon')) {
            let message, messageID;

            message = e.target.closest('.message');
            messageID = message.dataset.id;

            $.ajax(`/message/delete/${messageID}`, {
                type: 'delete'
            }).done(() => {
                e.target.parentNode.parentNode.parentNode.parentNode.removeChild(message);
            }).fail(() => {
                alert('Fail to delete message!');
            });
        }
    });

    if (elements.btnShwExtndDtails) {
        elements.btnShwExtndDtails.addEventListener('click', editTicketView.showExtndMdl.bind(null, ticket.ID));
    }


    /*EXTEND*/
    if (elements.extendFormBtn) elements.extendFormBtn.addEventListener('click', editTicketView.showExtendFormModal.bind(null, ticket.ID));

    elements.ticketDetailStore.addEventListener('click', editTicketView.displayContactNumbers);

    // if (elements. fixBtn) elements.fixBtn.addEventListener('click', ticket.markAsFixed.bind(ticket, user));

    if (elements.rejectDetailsBtn) elements.rejectDetailsBtn.addEventListener('click', editTicketView.showRejectDetails.bind(null, ticket.ID))

  if(elements.btnAddRelated){
    elements.btnAddRelated.addEventListener('click', function(){
        let id = $(this).data('rid');
        showModal();
        renderLoader(elements.modalContent);
        $.ajax({
                url : '/relate/'+id+'/ticket',
                type: 'GET',
                success : function(data){
                    clearLoader();
                       insertToModal(data);
                }
        });
    });
  }

  if(elements.btnShowAppStats){
    elements.btnShowAppStats.addEventListener('click',function(){
        showModal();
        renderLoader(elements.modalContent);
        let sdcid =  $(this).data('sdcid');
        $.ajax({
                url : '/show/'+sdcid+'/approverstats',
                type: 'GET',
                success : function(data){
                    clearLoader();
                       insertToModal(data.view);
                       $('#appstats_tbody').html(data.data);
                }
        });
    });
  }

  

 
 
 

};
