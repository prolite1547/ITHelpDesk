export default class CategoryC {

    static store(data){
        return $.ajax('/add/categoryC',{
            type: 'POST',
            data
        })
    }
}
