<template>
    <form>
        <div class="form-group" v-bind:class="{'has-error': errors.username}">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" class="form-control" v-model="username"/>
            <form-error v-if="errors.breed" :errors="errors">
                {{ errors.username }}
            </form-error>
        </div>
        <div class="form-group" v-bind:class="{'has-error': errors.email}">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" v-model="email"/>
            <form-error v-if="errors.email" :errors="errors">
                {{ errors.email }}
            </form-error>
        </div>
        <div class="form-group" v-bind:class="{'has-error': errors.password}">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" v-model="password"/>
            <form-error v-if="errors.password" :errors="errors">
                {{ errors.password }}
            </form-error>
        </div>
        <button type="submit" class="btn btn-primary" @click.prevent="signup">Signup</button>
    </form>
</template>

<script>
    export default {
        data() {
            return {
                username:'',
                email:'',
                password:'',
                errors:[]
            }
        },
        methods: {
            signup() {
                axios.post(
                    '/api/user',
                    {
                        username:this.username,
                        email:this.email,
                        password:this.password
                    },
                    {
                        headers:{'X-Requested-Width':'XMLHttpRequest'}
                    })
                    .then((response) => {
                        //console.log(response);
                        window.location = '/user/signin';
                    })
                    .catch(error => {
                        if (error.response) {
                            console.log(error);
                            this.errors = error.response.data;
                        }
                    });
            }
        }
    }
</script>