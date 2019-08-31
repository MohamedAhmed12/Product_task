<template>
        <div class="row">
            <!-- Start Main Content  -->
                    <Product-details :product="product" :categories="categories" :user_type="user_type"></Product-details>
            <!-- End Main Content  -->
        </div>
</template>

// start Style 
<style scoped>
    .small-text { font-size: 18px; }
    .title { font-size: 36px; }
</style>

// start script
<script>

    // Import admin page components 
    import ProductDetails from '../components/products/ProductDetails'

    export default {
        props: [
            'categories',
            'user_type'
        ],
        data(){
            return {
                product:[],
            }
        },
        components: {
            ProductDetails,
        },
        beforeMount(){
            
            // axios headers 
            axios.defaults.headers.common['Content-Type'] = 'applicationon'
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('storia.jwt')

            // set the url (endpoint/api) that we will send request to get product data
            let url = `/api/products/${this.$route.params.page}`;

            // If status is 200, set the response of get request to variables 
            axios.get(url)
                 .then(response =>  this.product = response.data.product );

        },
    }
   

</script>