export default class StoreVisitDetail {
    storeData(data){
        return $.ajax('/store-visit/details/save',{
            type: 'POST',
            data: data,
        })
    }
}
