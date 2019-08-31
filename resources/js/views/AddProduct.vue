<template>
    <div class="col-5 m-auto add-product">
        <!-- Start Add Product  -->
        <form >

            <!-- input the product name  -->
            <div class="form-group ml-4">
                <input class="form-control" type="text" name="name" placeholder="Product Name" v-model="product.name">
            </div>            

            <!-- input the price -->  
            <div class="form-inline mb-4 price position-relative"> 
                <span class="currency price h3 text-primary ">$</span> 
                <input class="form-control w-100 pl-4" type="number" v-model="product.price" max="10000">
            </div>
            

            <!-- Start input category -->
            <div class="form-group  ml-4">
                <select class="form-control" placeholder="Category Name" v-model=" product.category_id " >
                    <option v-for="(category, index) in categories" :key="index" :value="category.id">{{category.name}}</option>
                </select> 
            </div>
            <!-- End input category -->

            <!-- Start upload image  -->
            <input class="form-control-file mb-3 col-12 pl-0 ml-4" type="file" multiple @change="attachFile">
            <!-- End upload image  -->

            <!-- update Button display only if user is admin -->
            <button v-if="user_type === 1" class="btn btn-success edit-btn mb-3 mt-4 float-right" @click="updateProduct">Done</button>
        </form>
        <!-- End Add Product  -->
    </div>
</template>

<script>
    export default {
        props: [
            'categories',
            'user_type'
        ],
        data() {
            return{
                product: {
                    name        : '',
                    price       : '',
                    category_id : '',
                    image       : null
                },
                attachments : null,
            }
        },
        methods: {
             // fetch the attached files(images)
            attachFile(event) {
                this.attachments = event.target.files;
            },
            // update product in data base and in the show view 
            updateProduct (e) {
                            
                // prevent button from submitting or refresh the page
                e.preventDefault();

                /* First upload the image and get it's path */
                // if image is attached
                if( this.attachments != null){
                    /* construct a set of key/value pairs 
                     * And It uses the same format a form would use if the encoding type were set to "multipart/form-data".
                     */
                    var formData = new FormData();
                    
                    /*
                     * Iteate over any file sent over appending the files
                     * to the form data.
                     * 
                     */
                    for( var i = 0; i < this.attachments.length; i++ ){
                    
                        let attachment = this.attachments[i];

                        formData.append('images[' + i + ']', attachment);
                    }

                    // Set the header authrization to the axios request
                    axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('storia.jwt')

                    // define the axios request header to multipart/form-data as we will send file(image)
                    let headers = {'Content-Type': 'multipart/form-data'};

                    //  upload image and the url for the image is returned to us.
                    axios.post("/api/upload-file", formData, {headers}).then(response => {
                        
                        /* update the image attribute of the product with the url of first image that returned
                         * If user didn't upload image then return the same old image
                         */
                        this.product.image = response.data ? response.data[0] : this.product.image;
                        
                        // set the imges paths to images string by join the array and convert it to single strings 
                        this.images_str = response.data.join();
                                
                        /* Secondly update the product with the path of uploaded image*/


                        // make the request               
                        axios.post(`/api/products`,{
                                                        name         : this.product.name,
                                                        price        : this.product.price,
                                                        category_id  : this.product.category_id,
                                                        images       : this.images_str,

                                                    })
                                                    .then(response => {
                                                        //  redirect home 
                                                        this.$router.push('/')
                                                        });

                    });// end uploadfilerequest

                }// endif
            }//end updateProduct
        },
    }
</script>