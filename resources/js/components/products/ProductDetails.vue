<template>
    <div class="modal-content col-10 m-auto p-0"> 
        <div class="row no-gutters">

            <!-- left aside  -->
            <aside class="col-sm-12 col-md-5 border-right">
                <article class="gallery-wrap">
                    <!-- Start Main Image   -->
                    <div class="img-big-wrap">
                        <div><img :src="getGallery[0]" :alt="product.name" class="img-fluid"></div>
                    </div>
                    <!-- End Main Image   -->

                    <!-- Start Small gallery -->
                    <div class="img-small-wrap">
                        <div class="item-gallery" v-for="(image, index) in getGallery" :key="index"> <img :src="image"></div>
                    </div> 
                    <!-- End Small gallery -->
                    
                </article> <!-- gallery-wrap .end// -->
            </aside>

            <!-- Right Aside  (components) -->
            <aside class="col-sm-12 col-md-7">
                <article class="p-3 p-lg-4">
                
                <!-- Start Show Product  -->
                <div class="row" v-if="editable === false">                    
                    <h2 class="title col-sm-6 col-md-6 h1" v-html="product.name" ></h2>
            
                    <!-- price-detail-wrap .// -->  
                    <div class="offset-sm-2 offset-md-0 col-sm-2 col-md-12"> 
                        <var class="price h3 text-primary mt-5"> 
                            <span class="currency">$</span><span class="num">{{ product.price }}</span>
                        </var> 
                    </div>
                    <!-- price-detail-wrap .// -->

                    <!-- Start category -->
                    <div class="h5 mt-3 col-12"> 
                        Category: {{  product.category.name ? product.category.name : ''  }}
                    </div>
                    <!-- End category -->

                    <!-- Edit Button display only if user is admin -->
                   
                    <div class="div aciton-btns" v-if="user_type === 1" >
                        <button class="btn btn-success edit-btn mb-3 pr-3 pl-3" @click="editProduct">Edit</button>
                        <button class="btn btn-danger  edit-btn mb-3" @click="deleteProduct">Delete</button>
                    </div>
                    
                </div>
                <!-- End SHow Product  -->

                <!-- Start Edit Product  -->
                <div class="row"  v-else>
                    <form action="">
                        <!-- input the product name  -->
                        <div class="form-group col-10 ml-4">
                            <input class="form-control" type="text" name="name" placeholder="Product Name" v-model="product.name">
                        </div>            

                        <!-- input the price -->  
                        <div class="form-inline col-11 mb-4"> 
                            <span class="currency price h3 text-primary pr-1 pt-2">$</span> 
                            <input class="form-control col-11" type="number" v-model="product.price" max="10000">
                        </div>
                        

                        <!-- Start input category -->
                        <div class="form-group col-10 ml-4">
                            <select class="form-control" placeholder="Category Name" v-model=" product.category_id " >
                                <option v-for="(category, index) in categories" :key="index" :value="category.id">{{category.name}}</option>
                            </select> 
                        </div>
                        <!-- End input category -->

                        <!-- Start upload image  -->
                            <input class="form-control-file col-11 ml-4" type="file" multiple @change="attachFile">
                        <!-- End upload image  -->

                        <!-- update Button display only if user is admin -->
                        <button v-if="user_type === 1" class="btn btn-success edit-btn mb-3 mr-5 mt-4 float-right" @click="updateProduct">Update</button>
                    </form>
                </div>
                <!-- End Edit Product  -->

                </article> <!-- card-body.// -->
            </aside> <!-- col.// -->
        </div> <!-- row.// -->
    </div>
</template>

<script>

    import {request} from 'http';

    export default {
        props: [
           'categories',
           'product',
           'user_type'
        ],
        data() {
            return {
                editable    : false,
                attachments : null,
                images_str  : [],
                mainImage   : ''
            }
        },
        methods: {
            // At pressing on edit button toggle the value of editable between false and true 
            editProduct (e) {
                // prevent button from submitting or refresh the page
                e.preventDefault();
                this.editable = !this.editable
            },

            // fetch the attached files(images)
            attachFile(event) {
                this.attachments = event.target.files;
            },

            // update product in data base and in the show view 
            updateProduct (e) {
                            
                // prevent button from submitting or refresh the page
                e.preventDefault();

                /* First upload the image and get it's path */
                
                /* construct a set of key/value pairs 
                 * And It uses the same format a form would use if the encoding type were set to "multipart/form-data".
                 */
                var formData = new FormData();

                /*
                *  If there are attachment Iterate over any file sent over appending the files
                * to the form data.
                * 
                */
                if ( this.attachments != null) {
                    for( var i = 0; i < this.attachments.length; i++ ){
                
                        let attachment = this.attachments[i];

                        formData.append('images[' + i + ']', attachment);
                    }
                }
 
                // Set the header authrization to the axios request
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('storia.jwt')
                
                // define the axios request header to multipart/form-data as we will send file(image)
                let headers = {'Content-Type': 'multipart/form-data'};

                    //  upload image and the url for the image is returned to us.
                    axios.post("/api/upload-file", formData, {headers}).then(response => {
                        
                        /* update the images attribute of the product with the url of first image that returned
                         * If user didn't upload images then return the same old images
                         * join the array of images names and convert it to single strings
                         */
                        this.product.images = response.data ? response.data.join() : this.product.images;
                        
                        /* Secondly update the product with the path of uploaded image*/
                        // make the request               
                        axios.patch(`/api/products/${this.product.id}`,{
                                                                        name         : this.product.name,
                                                                        price        : this.product.price,
                                                                        category_id  : this.product.category_id,
                                                                        images       : this.product.images,

                                                                        }).then(response => {
                                                                                    // Reset the new category so we can get it's name to show in product details
                                                                                    this.product.category =  response.data.category ? response.data.category : this.product.category ;
                                                                                    console.log(response.data.cat, response.data.product,response.data )
                                                                                });

                    });

                // hide the edit mode and return to showing the product data 
                this.editable = !this.editable
            },

            // Delete product in data base 
            deleteProduct (e) {
                e.preventDefault();

                axios.delete(`/api/products/${this.product.id}`).then(
                    this.$router.push('/')
                )     

            },
        },
        computed: {
            // fetch images and set them to gallery array 
            getGallery : {

                // user getter to get the image and convert it to array 
                get(){
                    return this.product.images ? this.product.images.split(',') : '' ;
                },
                // then set it to galler 
                set(newVal){
                    this.gallery   = newVal
                },
            },
        }, 
    }
</script>