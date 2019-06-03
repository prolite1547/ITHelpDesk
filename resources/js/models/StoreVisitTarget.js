export default class StoreVisitTarget {
    storeData(data){
        return $.ajax('/store-visit/target/save',{
            type: 'POST',
            data: data,
        })
    }
}
