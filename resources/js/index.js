import {elements, hideModal, toggleFormGroups} from "./views/base";
import {ticketViewController,ticketAddController} from "./TicketViewController";
import {ticketPageController} from "./TicketPageController";
import {profileController} from "./ProfileController";
import {adminPageController} from  "./AdminPageController";
import {maintenancePageController} from "./maintenancePageController";
import {treasuryDashboardController} from "./treasury/index";
import {devProjsController} from "./devprojs/datatable";
import User from './models/User';



$(document).ready( function(){

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

/*ADDED SELECT2 PLUGIN*/
$.fn.select2.defaults.set("width", "auto");
$.fn.select2.defaults.set("dropdownAutoWidth", true);
if(elements.select2elements){
    elements.select2elements.select2();
}

elements.popupClose.addEventListener('click',() => {
    hideModal();
});

    const ticketView_route  = new RegExp("\/tickets\/view\/\\d+",'gm');
    const ticketAdd_route  = new RegExp("\/tickets\/add",'gm');
    const userProfile_route  = new RegExp("\/user\/profile\/\\d+",'gm');
    const tikcketPages_route  = new RegExp("\/tickets\/(open|my|ongoing|closed|all|fixed|expired)",'gm');
    const adminPage_route  = new RegExp("\/admin",'gm');
    const maintenancePage_route  = new RegExp("\/maintenance",'gm');
    const treasuryDashboard_route  = new RegExp("\/treasury/dashboard",'gm');
    const devprojs_route = new RegExp("\/show/devprojects",'gm');
    const pathName = window.location.pathname;

    switch (true){
        case ticketView_route.test(pathName):
            const user = new User();
            ticketViewController(user);
            break;
        case ticketAdd_route.test(pathName):
            elements.maintenanceCol.addEventListener('click',toggleFormGroups); /*EVENT LISTENER ON PLUS ICONS*/
            ticketAddController();
            break;
        case userProfile_route.test(pathName):
            profileController();
            break;
        case tikcketPages_route.test(pathName):
            ticketPageController();
            break;
        case adminPage_route.test(pathName):
            adminPageController();
            break;
        case maintenancePage_route.test(pathName):
            maintenancePageController();
            break;
        case treasuryDashboard_route.test(pathName):
            treasuryDashboardController();
            break;
        case devprojs_route.test(pathName):
            devProjsController();
        break;
        default:
            console.log('route not set');
    }

});