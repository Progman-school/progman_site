import { createRouter, createWebHistory } from 'vue-router'

import hello from './components/hello.vue'
import start from './components/pages/start.vue'
import test from './components/pages/test.vue'
import free from './components/pages/free.vue'
import conditions from './components/pages/conditions.vue'
import contacts from './components/pages/contacts.vue'
import checkCertificate from './components/pages/check_certificate.vue'
import studyPlan from './components/pages/study_plan.vue'
import consulting from './components/pages/consulting.vue'
import notFound from './components/not_found.vue'

const routes = [
    // the main page
    {
        name: null,
        path: '/:lang?',
        component: hello,
    },

    // menu pages
    {
        name: 'study plan',
        path: '/:lang?/study_plan',
        component: studyPlan,
    },
    {
        name: 'free',
        path: '/:lang?/free',
        component: free,
    },
    {
        name: 'conditions',
        path: '/:lang?/conditions',
        component: conditions,
    },
    {
        name: 'contacts',
        path: '/:lang?/contacts',
        component: contacts,
    },
    {
        name: 'check cert',
        path: '/:lang?/check_certificate',
        component: checkCertificate,
    },

    // non menu pages
    {
        name: '',
        path: '/:lang?/consulting',
        component: consulting,
    },

    // redirects from the old pages
    {
        name: 'test & start',
        path: '/:lang?/test',
        // component: test,
        redirect: '/:lang?/study_plan',
    },
    {
        name: null,
        path: '/:lang?/start',
        // component: start,
        redirect: '/:lang?/study_plan',

    },

    // 404
    {
        path: '*',
        component: notFound,
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})
export default router
