import {elements} from "./base";
import {catASelect2,catBSelect2} from "../select2";

export const dynamicFormContent = (id) => {
    /*dynamic form id*/
    elements.plusToggleContainer.querySelector('form').setAttribute('id',`${id}`);
    switch (id) {
        case 'category_a':
            generateForm('',true,false,false);
            break;
        case 'category_b':
            generateForm('A',false,false,false);
            break;
        case 'category_c':
            generateForm('B',false,false,false);
            break;
        default:
            alert('category group not found!')
    }

};

function generateForm(text,parent = false,newCat = false,btn = false) {
    elements.plusToggleContainer.querySelector('label[for=category]').textContent = `Category ${text}`;
    elements.plusToggleContainer.querySelector('#categoriesSelect').disabled = parent;
    elements.plusToggleContainer.querySelector('input[name=new_category]').disable = newCat;
    elements.plusToggleContainer.querySelector('.form-categoriesAdd__parent').style.display = parent ? 'none' : 'block';
    elements.plusToggleContainer.querySelector('input[name=new_category]').style.display = newCat ? 'none' : 'block';
    elements.plusToggleContainer.querySelector('button[data-action=addCategory]').style.display = btn ? 'none' : 'block';

    $('#categoriesSelect').select2(text === 'A' ? catASelect2 : catBSelect2);
}
