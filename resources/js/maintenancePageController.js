import {displayError, elements, elementStrings, setDisable, toggleFormGroups} from "./views/base";
import * as glboalScript from "./global";
import {branchSelect2} from "./select2";
import * as maintenanceView from "./views/v_maintenance";
import CategoryA from "./models/CategoryA";
import CategoryB from "./models/CategoryB";
import CategoryC from "./models/CategoryC";

export const maintenancePageController = () => {
    $(elementStrings.branchSelectContact).on('select2:select', glboalScript.toggleHiddenGroup);
    elements.addContactForm.addEventListener('submit',glboalScript.sendForm);
    $('#contactBranchSelect').select2(branchSelect2);
    elements.maintenanceCol.addEventListener('click',toggleFormGroups); /*EVENT LISTENER ON PLUS ICONS*/

    /*category radio inputs*/
    const categories_select = elements.plusToggleContainer.querySelectorAll('input[name=category]');
    /*add new categories form*/
    const addCatForm = elements.plusToggleContainer.querySelector('.form');

    /*Generate default form base on current selected category*/
    categories_select.forEach(el => {
        if(el.checked){
            maintenanceView.dynamicFormContent(el.id);
        }
    });

    /*Listen if a category is selected*/
    categories_select.forEach(el => {
        el.addEventListener('change', (e) => {
            if(e.target.checked){
                maintenanceView.dynamicFormContent(e.target.id);
            }
        })
    });

    /*listen for sumbit event on adding of category*/
    addCatForm.addEventListener('submit',(e) => {
        e.preventDefault();
        const form = e.target;
        console.log(form.elements);
        const submitBtn = form.elements[6];
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Please Wait..,';
        setDisable(submitBtn,true);
        const data = $(form).serialize();
        let model = '';
        switch (form.id) {
            case 'category_a':
                model = CategoryA;
                break;
            case 'category_b':
                model = CategoryB;
                break;
            case 'category_c':
                model = CategoryC;
                break;
            default:
                alert('Form not found!');
        }

        model.store(data)
            .done(() => {
                alert('Successfully added category!');
                submitBtn.textContent = originalText;
                setDisable(submitBtn,false);
            })
            .fail(data => {
                submitBtn.textContent = originalText;
                setDisable(submitBtn,false);
                displayError(data)
            });
    })

};
