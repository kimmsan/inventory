import Vue from 'vue'
import store from '../store'

// return formatted number
Vue.filter('numberFormat', function (number) {
    if(number){
        return Number(number).toFixed(2);
    }
});

// return short text
Vue.filter('shortText', function (str) {
    if(str){
        return (str.length > 30) ? str.substr(0, 30-1) + ' ...' : str;
    }
});

// return formatted currency
Vue.filter('withCurrency', function (number) {
    let currency = store.state.operations.appInfo.currency
    if(number > 0)
    {
        let newNumber = (Number(number).toFixed(2)).toLocaleString()
        return currency.position == 'left' ? currency.symbol + newNumber : newNumber + currency.symbol
    }else{
        return currency.position == 'left' ? currency.symbol + 0 : 0 + currency.symbol
    }
})


// return formatted currency
Vue.filter('withAbsoluteCurrency', function (number) {
    let currency = store.state.operations.appInfo.currency
    if(number > 0){
        let newNumber = (Number(number).toFixed(2)).toLocaleString()
        return currency.position == 'left' ? currency.symbol + newNumber : newNumber + currency.symbol
    }else{
        return currency.position == 'left' ? '-' + currency.symbol + Math.abs(number) : newNumber + currency.symbol
    }
    return
})


// return code with prefix
Vue.filter('withPrefix', function (code, prefix) {
    return prefix + '-' + code;
})
