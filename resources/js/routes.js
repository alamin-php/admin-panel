export let routes = [
    { path: '/dashboard', component: require('./components/admin/Dashboard.vue').default },
    { path: '/developer', component: require('./components/admin/Developer.vue').default },
    { path: '/users', component: require('./components/admin/Users.vue').default },
    { path: '/profile', component: require('./components/admin/Profile.vue').default },
  ]