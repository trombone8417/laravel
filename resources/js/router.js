import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

import firstPage from './components/pages/myFirstVuePage';
import newRoutePage from './components/pages/newRoutePage';
import hooks from './components/basic/hooks.vue';
const routes = [
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
];
export default new Router({
    // 使用真實路徑
    mode : 'history',
    routes
});
