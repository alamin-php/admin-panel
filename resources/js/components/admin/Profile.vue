<template>
        <div class="row justify-content-center">
          <div class="col-md-3 mt-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img class="profile-user-img img-fluid img-circle" :src="getProfilePhoto()" alt="User profile picture">
                </div>

                <h3 class="profile-username text-center">{{ this.form.name }}</h3>

                <p class="text-muted text-center">{{ this.form.type }}</p>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Followers</b> <a class="float-right">1,322</a>
                  </li>
                  <li class="list-group-item">
                    <b>Following</b> <a class="float-right">543</a>
                  </li>
                  <li class="list-group-item">
                    <b>Friends</b> <a class="float-right">13,287</a>
                  </li>
                </ul>

                <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- About Me Box -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">About Me</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <strong><i class="fas fa-info mr-1"></i> About</strong>

                <p class="text-muted">
                 {{ this.form.bio }}
                </p>

                <hr>                
                <strong><i class="fas fa-book mr-1"></i> Education</strong>

                <p class="text-muted">
                 {{ this.form.education }}
                </p>

                <hr>

                <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>

                <p class="text-muted">{{ this.form.location }}</p>
                </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-md-9 mt-3">
            <div class="card">
              <div class="card-header p-2">
                  <h4>User Profile</h4>
              </div><!-- /.card-header -->
              <div class="card-body">

                    <form class="form-horizontal">
                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                          <input v-model="form.name" type="text" name="name" class="form-control" id="inputName" placeholder="Name">
                             <has-error :form="form" field="name"></has-error>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                          <input v-model="form.email" name="email" type="email" class="form-control" id="inputEmail" placeholder="Email">
                             <has-error :form="form" field="email"></has-error>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputExperience" class="col-sm-2 col-form-label">About</label>
                        <div class="col-sm-10">
                          <textarea v-model="form.bio" name="bio" class="form-control" id="inputExperience" placeholder="About"></textarea>
                             <has-error :form="form" field="bio"></has-error>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputEducation" class="col-sm-2 col-form-label">Education</label>
                        <div class="col-sm-10">
                          <input v-model="form.education" type="text" name="education" class="form-control" id="inputEducation" placeholder="Education">
                          <has-error :form="form" field="education"></has-error>
                        </div>
                      </div>                      
                      <div class="form-group row">
                        <label for="inputEducation" class="col-sm-2 col-form-label">Location</label>
                        <div class="col-sm-10">
                          <input v-model="form.location" type="text" name="location" class="form-control" id="inputEducation" placeholder="location">
                          <has-error :form="form" field="location"></has-error>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputSkills" class="col-sm-2 col-form-label">Profile Photo</label>
                        <div class="col-sm-10">
                          <input type="file" @change="updateProfile" name="photo" class="form-input">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                          <input v-model="form.password" name="password" type="password" class="form-control" id="inputPassword" placeholder="Password">
                             <has-error :form="form" field="password"></has-error>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <button @click.prevent="updateInfo" type="submit" class="btn btn-danger">Submit</button>
                        </div>
                      </div>
                    </form>

                  <!-- /.tab-pane -->

                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
          </div>
          <!-- /.col -->

        <!-- /.row -->
      
        </div>

</template>

<script>
    export default {
                data(){
            return {
                 form: new Form({
                    id:'',
                    name : '',
                    email: '',
                    password: '',
                    type: '',
                    bio: '',
                    education: '',
                    location: '',
                    photo: ''
                })
            }
        },

        methods:{
            getProfilePhoto() {
                let photo = (this.form.photo.length > 200) ? this.form.photo : "/admin/image/user/" + this.form.photo;
                return photo;
            },
            updateInfo() {
                this.$Progress.start();
                if (this.form.password == '') {
                    this.form.password = undefined;
                }
                this.form.put('api/profile')
                    .then(() => {
                        Toast.fire({
                        icon: 'success',
                        title: 'Profile update in successfully'
                        })
                        Fire.$emit('AfterCreate');
                        this.$Progress.finish();
                    })
                    .catch(() => {
                        this.$Progress.fail();
                    });
            },
            updateProfile(e) {
                let file = e.target.files[0];
                let reader = new FileReader();
                let limit = 1024 * 1024 * 2;
                if (file['size'] > limit) {
                    swal({
                        type: 'error',
                        title: 'Oops...',
                        text: 'You are uploading a large file',
                    })
                    return false;
                }
                reader.onloadend = (file) => {
                    this.form.photo = reader.result;
                }
                reader.readAsDataURL(file);
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        created(){
            axios.get("api/profile")
            .then(({ data }) => (this.form.fill(data)));
        }
    }
</script>
