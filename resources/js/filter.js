import * as moment from 'moment';
import Vue from 'vue'

Vue.filter('upText', function (text) {
    return text.charAt(0).toUpperCase() + text.slice(1)
});

Vue.filter('timeFormat', (arg) => {
    return moment(arg).format("Do MMM YYYY");
});

Vue.filter('sortlength', function (text, length, suffix) {
    return text.substring(0, length) + suffix;
});