
require('./bootstrap');

//Vue.js support
window.Vue = require('vue');

//Laravel-vue pagination support
Vue.component('pagination', require('laravel-vue-pagination'));

//Gate frontend support
import Gate from "./Gate";
Vue.prototype.$gate = new Gate(window.user);

//Moments with filter support
import {filter} from './filter'

//Vue-router support
import VueRouter from 'vue-router'
Vue.use(VueRouter)

//Fire support
window.Fire = new Vue();

// V-form support
import { Form, HasError, AlertError } from 'vform'
window.Form = Form
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

//Sweet-alert2 support
import Swal from 'sweetalert2'
window.Swal = Swal
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 4000,
    timerProgressBar: true,
    onOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});
window.Toast = Toast

//Vue-progressbar support
import VueProgressBar from 'vue-progressbar'

const options = {
    color: '#38c170',
    failedColor: '#874b4b',
    thickness: '5px',
    transition: {
        speed: '0.2s',
        opacity: '0.6s',
        termination: 300
    },
    autoRevert: true,
    location: 'top',
    inverse: false
}

Vue.use(VueProgressBar, options)

//Router path for admin-panel support
import {routes} from './routes'

  const router = new VueRouter({
  mode: 'history',
  routes // short for `routes: routes`
  })


//Passport front-end support
Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('not-found', require('./components/NotFound.vue').default);
Vue.component('working', require('./components/Working.vue').default);

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);


const app = new Vue({
    router
  }).$mount('#app')
