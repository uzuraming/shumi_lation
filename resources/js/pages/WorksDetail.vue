<template>
  <div>
    <mdb-card class="card-body shadow-sm" style="">
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

            }

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
                await this.fetchWorksDetail()
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

<style>

</style>