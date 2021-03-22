<template>
    <div v-if="isLogin">
         <div v-if="!isVerified">
            <mdb-card color="red lighten-1" class="text-center">
                <mdb-card-body>
                    メール認証がまだ終わっていません。
                    <button @click="resend" class="btn btn-danger shadow-none">再送する</button>
                </mdb-card-body>
            </mdb-card>
        </div>
    </div>
       
</template>

<script>
import { mdbCard, mdbCardBody } from 'mdbvue';
export default {
    computed: {
      isVerified () {
          return this.$store.getters['auth/is_verified']
      },
      isLogin () {
          return this.$store.getters['auth/check']
      },
      
    },
    methods:{
        async resend(){
            const response = await axios.post('/api/email/resend');
            // 移動する
              this.$router.push({ name:'waitVerify' })
          

        }     
    },
    
    name: 'EmailVerifiedMessage',
    components: {
      mdbCard,
      mdbCardBody,
    
    }

}
</script>

<style>

</style>