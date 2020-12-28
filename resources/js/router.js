import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

import firstPage from './components/pages/myFirstVuePage';
import newRoutePage from './components/pages/newRoutePage';
import hooks from './components/basic/hooks.vue';
import methods from './components/basic/methods.vue';
import usecom from './vuex/usecom.vue';
// projest pages
import home from './components/pages/home.vue';
import tags from './admin/pages/tags.vue';
import category from './admin/pages/category.vue';
import adminusers from './admin/pages/adminusers.vue';
const routes = [
    // project routes...
    {
        path: '/testvuex',
        component:usecom,
    },
    {
        path: '/',
        component:home
    },
    {
        path: '/tags',
        component:tags
    },
    {
        path: '/category',
        component:category
    },
    {
        path: '/adminusers',
        component:adminusers
    },
    {
        path: '/my-new-vue-route',
        component:firstPage
    },
    // basic tutorials routes
    {
        path: '/my-new-vue-route',
        component:firstPage
    },
    {
        path: '/new-route',
        component:newRoutePage
    },
    {
        path: '/hooks',
        component:hooks
    },
    {
        path: '/methods',
        component:methods
    },
];
export default new Router({
    // 使用真實路徑
    mode : 'history',
    routes
});
