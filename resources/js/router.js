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
import login from './admin/pages/login.vue';
import role from './admin/pages/role.vue';
import assignRole from './admin/pages/assignRole';
const routes = [
    // project routes...
    {
        path: '/testvuex',
        component: usecom,
    },
    {
        path: '/',
        component: home,
        name:'home'
    },
    {
        path: '/tags',
        component: tags,
        name:'tags'
    },
    {
        path: '/category',
        component: category,
        name:'category'
    },
    {
        path: '/adminusers',
        component: adminusers,
        name:'adminusers'
    },
    {
        path: '/role',
        component: role,
        name:'role'
    },
    {
        path: '/assignRole',
        component: assignRole,
        name:'assignRole'
    },
    {
        path: '/login',
        component: login,
        name:'login'
    },
    {
        path: '/my-new-vue-route',
        component: firstPage
    },
    // basic tutorials routes
    {
        path: '/my-new-vue-route',
        component: firstPage
    },
    {
        path: '/new-route',
        component: newRoutePage
    },
    {
        path: '/hooks',
        component: hooks
    },
    {
        path: '/methods',
        component: methods
    },
];
export default new Router({
    // 使用真實路徑
    mode: 'history',
    routes
});
