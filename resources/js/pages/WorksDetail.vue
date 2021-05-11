<template>
  <div>
      <!-- ロード中 -->
      <div class="d-flex justify-content-center align-center m-5"  v-if="isLoad">
            <div class="spinner-grow spinner" role="status">
                <span class="sr-only">Loading...</span>
            </div>

        </div>
    
    <!-- 作品情報 -->
    <mdb-card class="card-body shadow-sm" style="" v-if="!isLoad">
        <mdb-card-text>
            <div class="text-center mt-2">
                <div class="h2">{{ work.title }}</div>
            </div>
            <div class="text-left mt-3">
                <div class="">作者：
                    <router-link class="mb-2" :to="{ name: 'userDetail', params: { user_id: work.user.id  }}" >{{work.user.name }}</router-link >
                </div>
            </div>
            
            <div v-html="work.content "></div>

            <div class="">
                    <router-link class="mb-2" :to="{ name: 'comments', params: { work_id: work.id  }}" >
                        {{work.comments_count }}件のコメント</router-link >
                </div>

            <div v-if="work.its_mine && isLogin" @click="$router.push({name:'editWork', params:work_id})" class="btn-circle-flat shadow mousepointer-hand"><span class="h2">+</span></div>

        </mdb-card-text>
    
    </mdb-card>
      
  </div>
</template>

<script>
import { mdbCard, mdbCardTitle, mdbCardText } from 'mdbvue';
import { OK } from '../util'
export default {
    data(){
        return{
            work:{
                title:[],
                content:[],
                user:[],

            },
            isLoad:true

        }
    },
    components: {
      mdbCard,
      mdbCardTitle,
      mdbCardText
    },
    props:{
        work_id:String,

    },
    methods:{
        // 作品を取得
        async fetchWorksDetail(){
                const response = await axios.get(`/api/works/${this.work_id}`)
        
                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                };

                console.log(response);
                this.work = response.data;

            },
 
    },
    watch: {
            $route: {
            async handler () {
                await this.fetchWorksDetail();
                this.isLoad=false;
            },
            immediate: true
        }
    },
    computed: {
            isLogin () {
                return this.$store.getters['auth/check']&& this.$store.getters['auth/is_verified'];
            },
            username () {
                return this.$store.getters['auth/username'];
            }
        }

}
</script>

<style scoped>
    .btn-circle-flat {
        display: inline-block;
        text-decoration: none;
        background: #2E2E2E;
        color: #FFF;
        width: 70px;
        height: 70px;
        line-height: 120px;
        border-radius: 50%;
        text-align: center;
        overflow: hidden;
        transition: .4s;
        position: fixed;
        bottom: 100px; 
        right: 20px;
        display: -webkit-flex;
        display: flex;
        -webkit-align-items: center; /* 縦方向中央揃え（Safari用） */
        align-items: center; /* 縦方向中央揃え */
        -webkit-justify-content: center; /* 横方向中央揃え（Safari用） */
        justify-content: center; /* 横方向中央揃え */
       

    }   

</style>