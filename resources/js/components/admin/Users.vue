<template>
        <div class="row justify-content-center" v-if="$gate.isAdminOrAuthor()">

          <div class="col-12 ">
            <div class="card mt-3">
              <div class="card-header">
                <h3 class="card-title">User's Table</h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="newModal">Add New <i class="fas fa-user-plus fa-fw"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th><select v-model="select" @change="delSelected">
                          <option value="">Select</option>
                          <option value="">Delete All</option>
                        </select>
                        </th>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Type</th>
                      <th>Modify</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(user,index) in users.data" :key="user.id">
                      <td><input type="checkbox" :value="user.id" v-model="userId"></td>
                      <td>{{index+1}}</td>
                      <td>{{ user.name }}</td>
                      <td>{{ user.email }}</td>
                      <td>{{ user.type|upText }}</td>
                      <td>
                        <a class="btn btn-primary btn-sm" href="#" role="button" @click="editModal(user)"><i class="fas fa-edit"></i></a>
                        <a class="btn btn-danger btn-sm" href="#" role="button" @click="deleteUser(user.id)"><i class="fas fa-trash"></i></a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="users" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          
          
          
          
          </div>




                        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNewTitle" v-show="!editmode">Add new</h5>
                <h5 class="modal-title" id="addNewTitle" v-show="editmode">Update info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form @submit.prevent="editmode ? updateUser() : createUser()">
            <div class="modal-body">
            <div class="form-group">
                <label>Name</label>
                <input v-model="form.name" type="text" name="name" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('name') }">
                <has-error :form="form" field="name"></has-error>
            </div>
            <div class="form-group">
                <label>E-mail</label>
                <input v-model="form.email" type="email" name="email" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('email') }">
                <has-error :form="form" field="email"></has-error>
            </div>
            <div class="form-group">
                <label>Type</label>
                <select v-model="form.type" id="type" name="typr" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('type') }">
                        <option value="">Select One</option>
                        <option value="admin">Admin</option>
                        <option value="author">Author</option>
                        <option value="user">User</option>
                        
                    </select>
                <has-error :form="form" field="type"></has-error>
            </div>
            <div class="form-group">
                <label>About</label>
                <textarea v-model="form.bio" id="bio" name="bio" class="form-control" placeholder="Short bio for user (Optional)"
                    :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
                <has-error :form="form" field="bio"></has-error>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input v-model="form.password" type="password" name="password" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('password') }">
                <has-error :form="form" field="password"></has-error>
            </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" v-show="!editmode">Create</button>
                <button type="submit" class="btn btn-primary" v-show="editmode">Update</button>
            </div>
            </form>
            </div>
        </div>
        </div>
        </div>
        <div v-else>
            <not-found></not-found>
        </div>


</template>

<script>
export default {
    data() {
        return {
            editmode:false,
            users:{},
            userId:[],
            select:'',
            clickAll: false,
            // Create a new form instance
            form: new Form({
                id:'',
                name: '',
                email:'',
                type:'',
                bio:'',
                photo:'',
                password: '',
            })
        }
    },
    methods:{
        selectAll(){
            this.clickAll = true
            for (var user in this.loadUsers) {
                this.userId.push(this.loadUsers[user].id)
            }
        },
        delSelected(){
            axios.get('/api/deluser/'+this.userId).then(()=>{
                this.userId = []
                Toast.fire({
                    icon: 'success',
                    title: 'User deleted in successfully'
                })
                Fire.$emit('AfterCreate');
            }).catch(()=>{
                Toast.fire({
                    icon: 'error',
                    title: 'Sorry, Something wrong!'
                })
            })
        },
        getResults(page = 1) {
            axios.get('api/users?page=' + page)
                .then(response => {
                    this.users = response.data;
                });
        },
        updateUser(){
            this.$Progress.start()
            this.form.put('/api/users/' + this.form.id)
                .then(() => {
                    $('#addNew').modal('hide')
                    Toast.fire({
                        icon: 'success',
                        title: 'User update in successfully'
                    })
                    Fire.$emit('AfterCreate');
                    this.$Progress.finish()
                }).catch(() => {
                    this.$Progress.fail()
                })
        },
        editModal(user){
            this.editmode = true;
            this.form.reset();
            this.form.clear();
            $('#addNew').modal('show')
            this.form.fill(user);
        },
        newModal(){
            this.editmode = false;
            this.form.reset();
            this.form.clear();
            $('#addNew').modal('show')
        },

        deleteUser(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    this.$Progress.start()
                    this.form.delete('/api/users/' + id)
                        .then(() => {
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                            Fire.$emit('AfterCreate')
                            this.$Progress.finish()
                        }).catch(() => {
                        Swal.fire(
                        'Sorry!',
                        'Your file Not deleted. You are not Permited',
                        'error'
                        )
                            this.$Progress.fail()
                        })
                }
            })
        },
        createUser(){
            this.$Progress.start()
            this.form.post('/api/users')
                .then(() => {
                    $('#addNew').modal('hide')
                    Toast.fire({
                        icon: 'success',
                        title: 'User created in successfully'
                    })
                    Fire.$emit('AfterCreate');
                    this.$Progress.finish()
                }).catch(() => {
                    this.$Progress.fail()
                })
        },
        loadUsers() {
            if(this.$gate.isAdminOrAuthor()){
                axios.get('/api/users').then(({data}) => {this.users = data})
            }
        },
    },
    mounted() {
        this.getResults();
        this.loadUsers();
        Fire.$on('AfterCreate', () => {
            this.loadUsers();
        })
    }
}
</script>
