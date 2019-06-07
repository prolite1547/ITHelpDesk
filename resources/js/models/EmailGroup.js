export default class EmailGroup{

    static getEmails(id){
        $.ajax(`/email/group/${id}/emails`,{
            type: 'GET'
        }).done(data => /*display the mails*/ $('.form-emailGroupAdd__email-table').html(data))
           .fail(() => alert('failed to get emails'));
    }
}
