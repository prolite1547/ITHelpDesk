import * as ticketTablesView from  './views/ticketTablesPage.js';

export const ticketPageController = () => {

    /*SET DEFAULT CONFIG FOR DATATABLES*/
    ticketTablesView.setDataTablesConf();

    /*TOGGLE SUBMENU ON CHECKBOX*/
    ticketTablesView.checkBoxListen();

    /*THIS IS WHERE THE FILTER FUNCTIONALITY LIVES*/
    ticketTablesView.filterFunction();

    /*Dynamic User Filter*/
   ticketTablesView.myElements.radios.forEach(el => {
       el.addEventListener('input',updateDOMFilterUser);
    })
};

function updateDOMFilterUser(e) {
    const filter_user = e.target.value;
    ticketTablesView.myElements.userLabel.textContent = e.target.previousElementSibling.textContent;

    ticketTablesView.myElements.userSelectInput.id = filter_user;
    ticketTablesView.myElements.userSelectInput.setAttribute('name',filter_user);
}
