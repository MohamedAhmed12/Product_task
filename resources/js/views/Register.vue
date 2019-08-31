<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">Register</div>
                    <div class="card-body">
                        <form>
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" v-model="name" required autofocus>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" v-model="email" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" v-model="password" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>
                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" v-model="password_confirmation" required>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary" @click="handleSubmit">
                                        Register
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
    //  We send the user data to the API for authentication and if we get a favourable response we save the token 
    export default {
        props: ['nextUrl'],
        data() {
            return{
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
            }
        },
        methods: {
             handleSubmit(e) {
                e.preventDefault();
               
                // check the password identical with passwrod confirmation and it's not empty
                if( this.password !== this.password_confirmation || this.password.length <= 0 ) {
                    this.password = "";
                    this.password_confirmation = "";
                    return alter('Passwords do not match')
                }

                // set variables
                let name = this.name
                let email = this.email
                let password = this.password
                let c_password = this.password_confirmation

                // send the user data to the API for authentication
                axios.post('api/register', {name, email, password, c_password}).then(response => {
                    // if we get a favourable response we save the token
                    let data = response.data;

                    // set the user and his token in the localStorage
                    localStorage.setItem('storia.user', JSON.stringify(data.user));
                    localStorage.setItem('storia.jwt', data.token);

                    // if user authanticated
                    if (localStorage.getItem('storia.jwt') != null) {
                        // change his status to loggedin
                        this.$emit('loggedIn');
                        
                        let nextUrl = this.$route.params.nextUrl ;
                        // if he was navigating to url then redirect him to it else redirect home page
                        this.$router.push((nextUrl != null ? nextUrl : '/'));
                    }
                })

            }
        }
    }
</script>