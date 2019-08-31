<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">Login</div>
                    <div class="card-body">
                        <form>
                            <div class="form-group row">
                                <label for="email" class="col-sm-4 col-form-label text-md-right">E-Mail Address</label>
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" v-model="email" required autofocus>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" v-model="password" required>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary" @click="handleSubmit">
                                        Login
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        data() {
            return{
                email: "",
                password: ""
            }
        },
        methods: {
            handleSubmit(e) {
                e.preventDefault();
                if ( this.password.length > 0) {
                    let email       =  this.email
                    let password    = this.password

                    // send post request with axios to the login endpoint params (email, password )
                    axios.post('api/login', {email, password}).then(response => {
                        // then set the user we get from the request response and it's type in variables
                        let user        = response.data.user;
                        let is_admin    = user.is_admin;

                        // set the user and his token in the localStorage
                        localStorage.setItem('storia.user', JSON.stringify(user));
                        localStorage.setItem('storia.jwt', response.data.token);

                        // if user is authorised
                        if(localStorage.getItem('storia.jwt') != null) {
                            // change his status to loggedin by emitting it to App.vue
                            this.$emit('loggedIn');
                           
                           // if he was navigating to url then redirect him to it 
                            if( this.$route.params.nextUrl != null) {
                              
                                this.$router.push(this.$route.params.nextUrl);
                           
                           } else { 
                            
                                // if not then redirect to admin page or userdashboard depending on user type
                                this.$router.push('/');
                            }

                        }
                    });
                }
            }
        }
    }
</script>