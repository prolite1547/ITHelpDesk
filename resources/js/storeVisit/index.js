import {store_visit_target,store_visit_details} from "../views/datatablesOptions";
import * as v_storeVisit from "../views/storeVisit";


export const storeVisitController = () => {

    v_storeVisit.el_container.on('click','button',(e) => {
        v_storeVisit.fetchForm(e.target.dataset.action)
    });

    v_storeVisit.el_container.on('click','svg.storeVisit__edit',(e) => {
        const [action,id] = v_storeVisit.getDataset(e.target);
        v_storeVisit.editModal(action,id);
    });

    v_storeVisit.el_container.on('click','svg.storeVisit__delete',(e) => {
        const [action,id] = v_storeVisit.getDataset(e.target);
        const confirmation = confirm('Are you sure you want to delete this item?');
        if(confirmation) v_storeVisit.deleteItem(id,action);
    });






};
