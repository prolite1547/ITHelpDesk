export const branchSelect2 = {
    ajax: {
        url: '/select/store',
        processResults: function (data) {
            return {
                results: data.data
            };
        }
    }
};

export const psitionSelect2 = {
    width: '100%',
    ajax: {
        url: '/select/position',
        processResults: function (data) {
            return {
                results: data.data
            };
        }
    }
};

export const deptSelect2 = {
    ajax: {
        url: '/select/department',
        processResults: function (data) {
            return {
                results: data.data
            };
        }
    }
};

export const exprtionSelect2 = {
    ajax: {
        url: '/select/expiration',
        processResults: function (data) {


            let reformattedArray  = data.data.map(obj => {
                let rObj,day;
                rObj = {};
                rObj.text = `${obj.text} hours`;
                rObj.id = obj.id;
               return rObj;
            });


            return {
                results: reformattedArray
            };
        }
    }
};

export const cntctSelect2 = {
    ajax: {
        url: '/select/contact',
        processResults: function (data) {
            data = $.map(data.data, (obj) => {
                return {
                    text: obj.store_name,
                    children: obj.contact_numbers.map(obj2 => {
                        return {
                            id: obj2.id,
                            text: obj2.number
                        }
                    })
                }
            });

            return {
                results: data
            };
        }
    }
};

export const userSelect2 = {
    width: '100%',
    ajax: {
        url: '/select/users',
        processResults: function (data) {
                data.data.unshift({id: 0,text: 'Others'});
                return {
                    results: data.data
                };
        }
    },
};

export const technicalUserSelect2 = {
    width: '100%',
    ajax: {
        url: '/select/techUsers',
        processResults: function (data) {
            return {
                results: data.data
            };
        }
    },
};

export const catBSelect2 = {
    ajax: {
        url: '/select/catB',
        processResults: function (data) {
            return {
                results: data.data
            };
        }
    },
};

export const catASelect2 = {
    ajax: {
        url: '/select/catA',
        processResults: function (data) {
            return {
                results: data.data
            };
        }
    },
};
