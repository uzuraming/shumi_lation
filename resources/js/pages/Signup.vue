<template>
  <div>
    <!-- Default form login -->
    <form @submit.prevent="signup">
      <p class="h4 text-center mb-4">Sign up</p>
      <label for="signup-name" class="grey-text">Your Name</label>
      <input v-model="registerForm.name" type="text" id="signup-name" class="form-control" />
      <label for="signup-email" class="grey-text">Your email</label>
      <input v-model="registerForm.email" type="email" id="signup-email" class="form-control" />
      <label for="signup-pass" class="grey-text">Your password</label>
      <input v-model="registerForm.password" type="password" id="signup-pass" class="form-control"/>
      <label for="signup-confirmation" class="grey-text">Confirmation</label>
      <input v-model="registerForm.password_confirmation" type="password" id="signup-confirmation" class="form-control"/>
      <div class="text-center mt-4">
        <button class="btn btn-indigo" type="submit">Signup</button>
      </div>
    </form>
    <!-- Default form login -->
    <mdb-modal :show="registerErrors !=null" @close="registerErrors == null">
        <mdb-modal-header :close="false" >
            <mdb-modal-title>警告</mdb-modal-title>
        </mdb-modal-header>
        <mdb-modal-body>
            <div v-if="registerErrors != null" class="errors">
                <ul v-if="registerErrors.name">
                <li v-for="msg in registerErrors.name" :key="msg">{{ msg }}</li>
              </ul>
              <ul v-if="registerErrors.email">
                <li v-for="msg in registerErrors.email" :key="msg">{{ msg }}</li>
              </ul>
              <ul v-if="registerErrors.password">
                <li v-for="msg in registerErrors.password" :key="msg">{{ msg }}</li>
              </ul>
            </div>
        </mdb-modal-body>
        <mdb-modal-footer>
            <mdb-btn color="secondary" @click.native="clearError()">Close</mdb-btn>
        </mdb-modal-footer>
    </mdb-modal>
  </div>
  
</template>

<script>
import { mdbModal, mdbModalHeader, mdbModalTitle, mdbModalBody, mdbModalFooter, mdbBtn } from 'mdbvue';
export default {
    data(){
        return{
            registerForm:{
                name:"",
                email:"",
                password:"",
                password_confirmation:""
            }
        }
    },
    components: {
      mdbModal,
      mdbModalHeader,
      mdbModalTitle,
      mdbModalBody,
      mdbModalFooter,
      mdbBtn
    },
    methods:{
        async signup(){
            // authストアのresigterアクションを呼び出す
            await this.$store.dispatch('auth/register', this.registerForm)
              if (this.apiStatus) {
              // トップページに移動する
              this.$router.push({ name:'waitVerify' })
          }
        },
        clearError () {
        this.$store.commit('auth/setRegisterErrorMessages', null)
      }
    },
    computed:{
        apiStatus () {
            return this.$store.state.auth.apiStatus
        },
        registerErrors () {
            return this.$store.state.auth.registerErrorMessages
        },
        
    },
    created () {
        this.clearError()
    }

}
</script>