<template>
    <form>
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
        <button type="submit" class="btn btn-primary" @click.prevent="signin">Signin</button>
    </form>
</template>

<script>
    export default {
        data() {
            return {
                email:'',
                password:'',
                errors:[]
            }
        },
        methods: {
            signin() {
                axios.post('/api/user/signin',{
                    email:this.email,
                    password:this.password
                },
                    {
                        headers:{'X-Requested-Width':'XMLHttpRequest'}
                    }
                ).then((response) => {
                    console.log(response);
                    const token = response.data.token;
                    const base64Url = token.split('.')[1];
                    const base64 = base64Url.replace('-','+').replace('_','/');
                    console.log(JSON.parse(window.atob(base64)));
                    localStorage.setItem('token',token);
                    window.location = '/pets';
                }).catch(error => {
                    if (error.response) {
                        this.errors = error.response.data;
                    }
                });
            }
        }
    }
</script>