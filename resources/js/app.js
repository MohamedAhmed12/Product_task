// /**
//  * First we will load all of this project's JavaScript dependencies which
//  * includes Vue and other libraries. It is a great starting point when
//  * building robust, powerful web applications using Vue and Laravel.
//  */

require('./bootstrap');

// require the vue paginator axios js file 
require('../../node_modules/vuejs-paginate/dist/index')


// /**
//  * The following block of code may be used to automatically register your
//  * Vue components. It will recursively scan this directory for the Vue
//  * components and automatically register them with their "basename".
//  *
//  * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
//  */

// import vue and vuew router 
window.Vue = require('vue');
import VueRouter from 'vue-router'

// use vue router 
Vue.use(VueRouter)

/* Defined routes for our application */

// main view
import App from './views/App'

// user views 
import Login from './views/Login' 
import Register from './views/Register' 

// Shop page to show all products and act as home page
import Shop from './views/Shop'

// single product view which wil have child components( show - edit )
import SingleProduct from './views/SingleProduct'

// Add product view 
import AddProduct from './views/AddProduct'

// Categories view
import Category from './views/Category'

// Registered routes to the Vue instance so it is available to all Vue components.
const router = new VueRouter({
    mode: 'history',
    routes: [ // pass the routes through router to add it to my app
        {
            path: '/',
            name: 'shop',
            component: Shop
        },
        {
            path: '/login',
            name:'login',
            component: Login
        },
        {
            path: '/register',
            name: 'register',
            component: Register
        },
        {
            //* Display single product page */
            path: '/products/',
            name: 'single-product',
            component: SingleProduct
        },
        {
            /* Add product */
            path: '/products/add', 
            name: 'add-product',
            component: AddProduct,
            // only accessed by admin 
            meta: {
                reuiresAuth: true,
                is_admin: true
            }
        },
        {
            /* Display child component of single product ( show page ('/id')   ) */

            // path will be (/products/this.$route.params.id/this.$route.params.page)
            path: '/products/:page', 
            name: 'single-product-pages',
            component: SingleProduct,
        },
        {
            path: '/category',
            name: 'category',
            component: Category,
            // only accessed by admin 
            meta: {
                reuiresAuth: true,
                is_admin: true
            }
        },
       
    ],
});

// set up the beforeEach middleware on the router that checks each route before going to it
router.beforeEach( (to, from ,next) => {
    // if this route require Authantication
    if(to.matched.some(record => record.meta.reuiresAuth)) {
       
        // if user isn't authenticated redirect to login page
        if(localStorage.getItem('storia.jwt') == null) {
           
            next({
                path: '/login',
                params: {nextUrl: to.fullPath}
            })

        } else { // if user authenticated

            // set user to variable
            let user = JSON.parse(localStorage.getItem('storia.user'))

            // check if the route is restricted to admin users
            if(to.matched.some(record => record.meta.is_admin)) {
               
                // If the user  is admin
                if( user.is_admin == 1) {
                    next()
                } else {
                    // if not admin redirect to shop page
                    next({name: 'shop'})
                }

            }

            // if the route isn't restricted to users(neither regular nor admins)
            next()
        }

    } else {

        // if this route doesn't require Authantication
        next()

    }

});


/**
 * This instantiates the Vue application in this global instance (app), 
 * we mount the {App} component because the VueRouter needs it to switch between all the other components.
 */




const app = new Vue({
    el: '#app',
    components: {App},
    router,
});
