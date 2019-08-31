<template>
    <div class="text-center">
        <h1 class="text-dark">The Categories</h1>
     


        <table class="table table-dark col-1sm-2 col-md-5 m-auto">
            <thead>
                <tr>
                    <th scope="col" class="number-header pt-4">#</th>
                    <th scope="col">

                        <span class="pt-4 col cat-header">Category </span> 

                         <!-- add categories button  -->
                        <button class="float-right btn btn-dark " @click="showField = true">
                            <span class="h3">+</span>
                        </button>
                    </th>
                </tr>
            </thead>
            <tbody>

                 <!-- Start the row of adding category form -->
                 <tr v-if="showField === true">
                    <th scope="row"></th>
                    <th class="form-inine">
                        <div class="row">
                            <input type="text" class="form-control col-8" placeholder="Enter Category Name" id="cat" maxlength="50" v-model="newCategory">
                            <!-- add categories button  -->
                            <button class="add-cat btn btn-primary col-2" @click="addCategory">
                                Add
                            </button>
                        </div>
                    </th>
                </tr>
                 <!-- End the row of adding category form -->

                <!-- Start rows -->
                <tr v-for="(category, index) in categories" :key="index">
                    <th scope="row">{{index + 1}}</th>
                    
                    <td>
                        <!-- Start default Mode  -->
                        <div class="row show-category">
                            
                            <p class="col-8 cat-name">{{category.name}}</p>

                            <div class="col-4 form-inline ationBtns">
                                <button class="edit-cat btn btn-primary" @click="showEditField">Edit</button>
                                <button class="delet-cat btn text-danger ml-2" @click="delCategory($event, category.id, index)">X</button>
                            </div>

                        </div>
                        <!-- End default Mode  -->
                        
                        <!-- Start editing mode  -->
                         <div class="row edit-form d-none">
                            <input type="text" class="form-control col-8" placeholder="Enter Category Name" id="cat" maxlength="50" v-model="category.name">
                            <!-- add categories button  -->
                            <button class="add-cat btn btn-primary col-2" @click="updateCategory($event, category.id, category.name, index)">
                                Update
                            </button>
                        </div>
                        <!-- End editing mode  -->
                    </td>

                </tr>
                <!-- Ends rows -->
            </tbody>
        </table>
    </div>
</template>

<script>

export default {
    props: [
        'categories'
    ],
    data() {
        return {
            showField   : false,
            newCategory : null,
            updated     : null,
        }
    },
    methods: {           
        // Add new category
        addCategory () {

            // send the header of token to authorize the request 
            axios.defaults.headers.common['Content-Type'] = 'application/json'
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('storia.jwt')
        
            // send the request to add category to database
            axios.post('/api/categories', {name: this.newCategory} ).then(response => {
                // push(add)  the new one to the displayed table
                this.categories.push(response.data.category)

            })

            // hide the form
            this.showField = false

            // Empty the newcategory variable
            this.newCategory = null

        },
        showEditField (e) {

            // prevent button from submitting or refresh the page
            e.preventDefault();
            
            // Get the pressed button
            let editBtn = $(e.target);

            // get specific parent(show-category) of the pressed button then get this parent sibling(edit-form to) to switch visibilty between them
            editBtn.parents('.show-category').addClass('d-none');
            editBtn.parents('.show-category').siblings('.edit-form').removeClass('d-none')
            
    
        },
        updateCategory (e, id, updated, index) {
           
           // prevent button from submitting or refresh the page//
            e.preventDefault();
            
            // set Authantication of token for axios heder 
            axios.defaults.headers.common['Content-Type'] = 'application/json'
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('storia.jwt')

            // send the request and then update the category table with the response 
            axios.patch(`/api/categories/${id}`, { name : updated } ).then(response => {
                
                // update the category in categories array and table 
                this.categories[index] = response.data.category

                // Get the pressed button
                let updateBtn = $(e.target);
                
               // After submitting get specific parent(show-category) of update button then get this parent sibling(edit-form to) to switch visibilty between them
                updateBtn.parents('.edit-form').addClass('d-none');
                updateBtn.parents('.edit-form').siblings('.show-category').removeClass('d-none')

            });

        },
        delCategory (e, id, index) {
            
            // prevent button from submitting or refresh the page
             e.preventDefault();

                // set Authantication of token for axios heder 
                axios.defaults.headers.common['Content-Type'] = 'application/json'
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('storia.jwt')

                axios.delete(`/api/categories/${id}`).then( response =>
                    this.categories.splice(index, 1)
                )   
        }
    }
}
</script>