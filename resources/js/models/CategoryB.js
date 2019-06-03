export default class CategoryB {

    static store(data){
        return $.ajax('/add/categoryB',{
            type: 'POST',
            data
        })
    }
}
