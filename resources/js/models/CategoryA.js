export default class CategoryA {

    static store(data){
        return $.ajax('/add/categoryA',{
            type: 'POST',
            data
        })
    }
}
