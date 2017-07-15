<template>
    <div>
        <div class="alert alert-success" v-if="submitted">
            {{message}}
        </div>
        <div class="alert alert-danger" v-if="issues">
            {{issues}}
        </div>
        <div class="row">
            <div class="col-md-6">
                <h4>All Pets</h4>
                <ul class="list-group">
                    <li class="list-group-item" v-for="pet in list">
                        {{ pet.breed }} {{pet.name}}
                        <span class="pull-right">
                            <button @click="showPet(pet.id)" class="btn btn-primary btn-xs">Edit</button>
                            <button @click="deletePet(pet.id)" class="btn btn-danger btn-xs">Delete</button>
                        </span>
                    </li>
                </ul>
            </div>
            <div class="col-md-6">
                <h4>New/Edit Pet</h4>
                <form action="#" @submit.prevent="edit ? updatePet(pet.id) : createPet()">
                    <div class="form-group" v-bind:class="{'has-error': errors.breed}">
                        <label for="breed">Breed</label>
                        <input type="text" id="breed" name="breed" class="form-control" v-model="pet.breed"  placeholder="please input breed" required />
                        <form-error v-if="errors.breed" :errors="errors">
                            {{ errors.breed }}
                        </form-error>
                    </div>

                    <div class="form-group" v-bind:class="{'has-error': errors.age}">
                        <label for="age">Age(months)</label>
                        <input type="text" id="age" name="age" class="form-control" v-model="pet.age" placeholder="please input age" required/>
                        <form-error v-if="errors.age" :errors="errors">
                            {{ errors.age }}
                        </form-error>
                    </div>

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" class="form-control" v-model="pet.name" placeholder="please input name" />
                    </div>

                    <div class="form-group" v-bind:class="{'has-error': errors.price}">
                        <label for="price">Price</label>
                        <input type="text" id="price" name="price" class="form-control" v-model="pet.price" placeholder="please input price, like 100.39" required/>
                        <form-error v-if="errors.price" :errors="errors">
                            {{ errors.price }}
                        </form-error>
                    </div>

                    <div class="form-group" v-bind:class="{'has-error': errors.list_date}">
                        <label for="list_date">List Date</label>
                        <input type="text" id="list_date" name="list_date" class="form-control" v-model="pet.list_date" placeholder="please input list date" required/>
                        <form-error v-if="errors.list_date" :errors="errors">
                            {{ errors.list_date }}
                        </form-error>
                    </div>

                    <div class="form-group" v-bind:class="{'has-error': errors.sale_date}">
                        <label for="sale_date">Sale Date</label>
                        <input type="text" id="sale_date" name="sale_date" class="form-control" v-model="pet.sale_date" placeholder="please input sale date" />
                        <form-error v-if="errors.list_date" :errors="errors">
                            {{ errors.list_date }}
                        </form-error>
                    </div>

                    <span class="input-group-btn">
                <button v-show="!edit" type="submit" class="btn btn-primary">New Pet</button>
                <button v-show="edit" type="submit" class="btn btn-primary">Edit Pet</button>
        </span>
                </form>
            </div>

        </div>



    </div>
</template>

<script>
    import FormError from './FormError.vue';
    export default{
        mounted() {
            this.fetchPetList();
        },
        components: {
            FormError,
        },
        data: function () {
            return {
                edit:false,
                submitted:false,
                list: [],
                pet: {
                    id: '',
                    breed:'',
                    age:'',
                    name:'',
                    price:'',
                    list_date:'',
                    sale_date:''
                },
                errors:[],
                issues:'',
                message:''
            };
        },

        methods: {
            fetchPetList: function () {
                axios.get('/api/pets', {}).then((response) => {
                    this.list = response.data.data;
                })
            },
            createPet:function () {
                const token = this.checkToken();
                axios.post('/api/pet?token='+token,{
                    breed:this.pet.breed,
                    age:this.pet.age,
                    name:this.pet.name,
                    price:this.pet.price,
                    list_date:this.pet.list_date,
                    sale_date:this.pet.sale_date
                }).then((response) => {
                    console.log(response);
                    this.init();
                    this.edit = false;
                    this.submitted = true;
                    this.message = response.data.message;
                    this.fetchPetList();
                }).catch(error => {
                    if (error.response) {
                        this.errors = error.response.data;
                        if(error.response.data.error) {
                            this.issues = error.response.data.error;
                        }
                    }
                });

            },
            updatePet: function (id) {
                const token = this.checkToken();
                axios.put('/api/pet/'+id+'?token='+token,{
                    breed:this.pet.breed,
                    age:this.pet.age,
                    name:this.pet.name,
                    price:this.pet.price,
                    list_date:this.pet.list_date,
                    sale_date:this.pet.sale_date
                }).then((response) => {
                    this.message = response.data.message;
                    this.fetchPetList();
                    this.init();
                    this.edit=false;
                }).catch(error => {
                    if (error.response) {
                        if(error.response.data.error) {
                            this.issues = error.response.data.error;
                        }

                    }
                });
            },
            showPet:function (id) {
                const token = this.checkToken();
                this.submitted=false;
                axios.get('/api/pet/'+id+'?token='+token).then((response) => {
                    var petData = response.data.data;
                    this.pet.id = petData.id;
                    this.pet.breed = petData.breed;
                    this.pet.age = petData.age;
                    this.pet.name = petData.name;
                    this.pet.price = petData.price;
                    this.pet.list_date = petData.list_date;
                    this.pet.sale_date = petData.sale_date;
                }).catch(error => {
                    if (error.response) {
                        if(error.response.data.error) {
                            this.issues = error.response.data.error;
                        }

                    }
                });
                this.edit=true;
            },
            deletePet:function (id) {
                const token = this.checkToken();
                axios.delete('/api/pet/'+id+'?token='+token).then((response) => {
                    this.operation_success = true;
                    this.message = response.data.message;
                    this.fetchPetList();
                }).catch(error => {
                    if (error.response) {
                        if(error.response.data.error) {
                            this.issues = error.response.data.error;
                        }
                    }
                });
            },
            init:function(){
                this.pet.id = '';
                this.pet.breed = '';
                this.pet.age = '';
                this.pet.name = '';
                this.pet.price = '';
                this.pet.list_date = '';
                this.pet.sale_date = '';
                this.errors='';
            },
            checkToken:function() {
                const token = localStorage.getItem('token');
                if(!token) {
                    this.issues = 'please sigin in first';
                    return false;
                }
                return token;
            }
        }
    }
</script>