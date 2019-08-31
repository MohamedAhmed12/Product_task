<template>
    <div>

    <!-- Start Navbar -->
    <nav class="navbar navbar-expand-md navbar-dark bg-dark navbar-laravel">
        <div class="container-fluid">
            <router-link :to="{name: 'shop'}" class="navbar-brand">BigStor</router-link>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Start Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">

                    <!-- if user loggedin and is admin Add Product page -->
                    <span>
                        <router-link :to="{ name: 'add-product'}" class="nav-link" v-if="user_type === 1">Add Product</router-link>
                    </span>
                   
                    <!-- if user loggedin and is admin show Category page -->
                    <span>
                        <router-link :to="{ name: 'category' }" class="nav-link" v-if="user_type === 1">Categories</router-link>
                    </span>
                    
                    <!-- if guest isn't loggedin display login and register links -->
                    <router-link :to="{ name: 'login' }" class="nav-link" v-if="!isLoggedIn">Login</router-link>
                    <router-link :to="{ name: 'register' }" class="nav-link" v-if="!isLoggedIn">Register</router-link>
                   
                    <span v-if="isLoggedIn">
                        <li class="nav-link" v-if="user_type == 0"> Hi, {{name}}</li>
                        <li class="nav-link" v-if="user_type == 1"> Hi, {{name}}</li>
                    </span>
                    <li class="nav-link" v-if="isLoggedIn" @click="logout"> Logout</li>
                </ul>
                <!-- End Right Side Of Navbar -->
            </div>
        </div>

    </nav>
    <!-- End Navbar -->

    <!-- Start Categories bar  -->
    <div class="container-fluid">
        <div class="row ">
            <ul class="nav w-100 text-center">
                <li v-for="(category, index) in categories" :key="index" class="col-12 col-sm btn-secondary nav-link" @click="getCategory(category)">
                    <router-link :to="{ name: 'shop' }" class="text-light"> {{category.name}} </router-link>
                </li>
            </ul>
        </div>
    </div>    
    <!-- End Categories bar  -->

    <!-- Satrt vue-router loader -->
    <main class="container mt-5 mb-5">      
        <router-view @loggedIn="change" :selectedCategory="selectedCategory" :categories="categories"  :user_type="user_type"></router-view>
    </main>
    <!-- End vue-router loader -->

    </div>
</template>


<script>

    export default {
        data() {
            return{
                categories           : [], // categories objects with products of each category
                selectedCategory     : null,
                name                 : null,
                user_type            : 0,
                isLoggedIn           : localStorage.getItem('storia.jwt') != null,
            }
        },
        beforeMount(){
            
            // get request to fetch categories and each category products 
            axios.get('api/categories').then(response => this.categories = response.data);

        },
        mounted() {
            // set the llocastorage user, name, usertype
            this.setDefault();
        },
        methods: {
            setDefault() {
                if (this.isLoggedIn) {
                    let user        = JSON.parse( localStorage.getItem('storia.user') ) 
                    this.name       = user.name
                    this.user_type  = user.is_admin
                }
            },
            change() {
                this.isLoggedIn = localStorage.getItem('storia.jwt') != null
                this.setDefault()
            },
            logout() {
                // delete data from local storage 
                localStorage.removeItem('storia.jwt')
                localStorage.removeItem('storia.user')
                
                // reset the variables 
                this.name       = null;
                this.user_type  = 0;

                // call change function to reset the variables
                this.change()
                
                // redirect home 
                this.$router.push('/')
            },
            getCategory (category = this.categories) {
                this.selectedCategory = category;
            }
        }
    }
</script>