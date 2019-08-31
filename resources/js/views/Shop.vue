<template>
    <div>

        <!-- Start main content -->
        <div class="shop container-fluid">
                <div class="row">
                
                <!-- Start Products -->
                <div class="col-sm-12">
                    <div class="row">
                        <!-- Start Product Card  -->
                        <div class="col-md-3" v-for="(product, index) in products" :key="index">
                            <figure class="card card-product">
                                <div  class="img-wrap">
                                    <img :src="product.images.split(',')[0]" :alt="product.name">
                                </div>
                                <figcaption class="info-wrap">
                                    <router-link :to="{ path: 'products/' + product.id }" class="title text-dark">{{ product.name }}</router-link>
                                    <div class="action-wrap">
                                        
                                        <!-- <router-link :to="{ path: 'products/' + product.slug }" class="btn btn-primary float-right">Add to cart</router-link> -->
                                    <!-- <router-link :to="{ path: '/checkout/' + product.slug }" class="col-md-4 btn btn-sm btn-primary float-right">Buy Now</router-link> -->
                                        <div class="price-wrap h5">
                                            <span class="price-new">$ {{product.price}}</span>
                                        </div> <!-- price-wrap.// -->
                                    </div> <!-- action-wrap -->
                                    <div class="float-right mt-3" v-if="selectedCategory"> {{selectedCategory.name}}</div>
                                </figcaption>
                            </figure> <!-- card // -->
                        </div> 
                        <!-- End Product Card  -->
                    </div>
                </div>
                <!-- End Products -->
            
            </div>
        </div>
        <!--  End main content -->
    
        <!-- Start Pagination  -->
        <nav aria-label="...">
            <ul class="pagination">

                <!-- First Button -->
                <li class="page-item">
                    <a @click="paginate(pagination.first_page)" class="page-link btn" tabindex="-1" aria-disabled="true" v-if="pagination.prev_page_url"> 
                        First
                    </a>
                </li>

                <!-- previous button  -->
                <li class="page-item">
                    <!-- if it's not First  page -->
                    <a @click="paginate(pagination.current_page - 1)" class="page-link btn" tabindex="-1" aria-disabled="true" v-if="pagination.prev_page_url"> 
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                    <!-- else -->
                    <a class="page-link btn disabled"  v-else>
                        <span aria-hidden="true">&laquo;</span>
                    </a> 
                </li>
                
                <!-- previous page  -->
                <li class="page-item" v-if="pagination.prev_page_url">
                    <a @click="paginate(pagination.current_page - 1 )" class="page-link">
                        {{ pagination.current_page - 1 }}
                    </a>
                </li>

                <!-- current Page  -->
                <li class="page-item active" aria-current="page">
                    <a @click="paginate(pagination.current_page)" class="page-link">
                        <!-- if there is value in pagination currentpage get it else put page number to be 1 -->
                        {{ pagination.current_page }}
                        <span class="sr-only">(current)</span>
                    </a>
                </li>

                <!-- next Page  -->
                <li class="page-item" v-if="pagination.next_page_url">
                    <a @click="paginate(pagination.current_page + 1 )" class="page-link">
                        {{ pagination.current_page + 1 }} 
                    </a>
                </li>

                <!-- next Button -->
                <li class="page-item">
                    <!-- if it's not last  page -->
                    <a @click="paginate(pagination.current_page + 1)" class="page-link btn" tabindex="-1" aria-disabled="true" v-if="pagination.next_page_url"> 
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                    <!-- else -->
                    <a class="page-link btn disabled"  v-else>
                          <span aria-hidden="true">&raquo;</span>
                    </a> 
                </li>

                <!-- Last Button -->
                <li class="page-item">
                    <a @click="paginate(pagination.last_page)" class="page-link btn" tabindex="-1" aria-disabled="true" v-if="pagination.next_page_url"> 
                        Last
                    </a>
                </li>

            </ul>
        </nav>
        <!-- End Pagination  -->

    </div>
</template>

<script>

 export default {
        props: [
            'categories',
            'selectedCategory',
            'user_type',
        ],
        data() {
            return{
                products    : [],
                pagination  : null,
            }
        },
         watch: {
            // This would be called anytime the value of selectedCategory changes( i.e: when category selected)
            selectedCategory(newValue, oldValue) {
                // set the products of selected category in products array 
                this.selectedCategory !== null ? this.products = this.selectedCategory.products : '';
            }
        },
        mounted() {
            if (this.selectedCategory == null ) {

                // if there is no category selected then fetch all the products with paginate to page 1
                this.paginate(1)
            
            } else {

                // if category selected fetch it's products
                this.products = this.selectedCategory.products

            }
            
        }, methods: {
            paginate (page) {
                // get request with page number as parameter 
                axios.get('api/products?page=' + page).then(response => {
                    
                    // set pagination
                    this.pagination = response.data;

                    // set products array 
                    this.products = this.pagination.data; 

                });
            }
        }
       
    }

</script>