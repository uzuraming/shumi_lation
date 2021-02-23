<template>
    <div>
        <div class="container mt-3">
            <div class="row">
                <div class="col-sm-6">
                    <mdb-card class="shadow-none">
                        <mdb-card-image src="https://mdbootstrap.com/img/Photos/Horizontal/Nature/4-col/img%20%286%29.jpg" alt="Card image cap"></mdb-card-image>
                        <mdb-card-body>
                            <mdb-card-title>{{userInfo.user.name}}</mdb-card-title>
                            <mdb-card-text> {{userInfo.user.profile}}</mdb-card-text>
                            <mdb-card-text> {{userInfo.user.followers_count}}Follower</mdb-card-text>
                            <mdb-card-text> {{userInfo.user.followings_count}}Followings</mdb-card-text>
                        <div v-if="this.isLogin && !userInfo.its_me" >
                            <div v-if='!userInfo.is_following'><mdb-btn :disabled="is_processing" @click="follow"  color="primary">Follow</mdb-btn></div>
                            <div v-if='userInfo.is_following'><mdb-btn :disabled="is_processing" @click="unFollow" color="danger">unFollow</mdb-btn></div>
                        </div>
                        
                        
                        </mdb-card-body>
                    </mdb-card>
                </div>



                <div class="col-sm-6">
                    <div d-flex class="d-flex w-100 justify-content-between">
                        <button :disabled="tab=='timeline'" @click="tab='timeline'" class=" w-100 btn btn-elegant">TimeLine</button>
                        <button :disabled="tab=='work'"  @click="tab='work'" class="w-100 btn btn-elegant">Works</button>
                    </div>

                

                    <div v-if="tab=='work'">
                    <mdb-list-group flush v-for="work in userInfo.works" :key="work.id">
                        <mdb-list-group-item v-for="d in work" :key="d.id">
                            <div>
                                <div class="d-flex w-100 justify-content-between">
                                </div>
                                <p class="mb-2">
                                    <router-link class="mb-2" :to="{ name: 'workDetail', params: { work_id: d.id }}" >{{d.title}}</router-link >
                                </p>
                                <small>{{d.created_at}}</small>
                                
                            </div>
                        </mdb-list-group-item>
                        

                    </mdb-list-group>
                    <div class="d-flex w-100 justify-content-between">
                        <button :disabled="!this.moreButtonStat.work.isActive" v-if="tab=='work'" @click="moreWork" class="btn btn-elegant w-100">{{this.moreButtonStat.work.sentence}}</button>
                    </div>
                    </div>
                    
                    <div v-if="tab=='timeline'" >
                        <mdb-list-group flush  v-for="timeline in userInfo.timelines" :key="timeline.id">
                        
                                <mdb-list-group-item v-for="d in timeline" :key="d.id">
                                    <div >
                                        <div class="d-flex w-100 justify-content-between">
                                        </div>
                                        <p class="mb-2">
                                            {{d.content}}
                                        </p>
                                        <small>{{d.created_at}}</small>
                                        
                                    </div>
                                </mdb-list-group-item>
                        
                            
                        </mdb-list-group>
                        <div class="d-flex w-100 justify-content-between">
                            <button :disabled="!this.moreButtonStat.timeline.isActive" v-if="tab=='timeline'" @click="moreTimeline" class="btn btn-elegant w-100">{{this.moreButtonStat.timeline.sentence}}</button>
                        </div>
                    </div>


                </div>
                

                    


                    <!-- <div class="d-flex justify-content-center" >
                        <Pagination :component="'timelines'" :current-page="currentPage" :last-page="lastPage" />
                    </div> -->
                </div>
            </div>
        </div>

    </div>
    
</template>

<script>
import { OK } from '../../util'
import { mdbCard, mdbCardImage, mdbCardBody, mdbCardTitle, mdbCardText, mdbBtn,mdbListGroup, 
        mdbListGroupItem, mdbTabs} from 'mdbvue'
export default {
    data(){
        return{
            userInfo:{
                user:[],
                timelines:[],
                works:[],
                is_following:'',
                its_me:false,
            },
            tab:'timeline',
            pagination:{
                timeline:1,
                work:1,
            },
            buttonMore:{
                timeline:true,
                work:true,
            },
            moreButtonStat:{
                timeline:{
                    isActive:true,
                    sentence:'More'
                },
                work:{
                    isActive:true,
                    sentence:'More'
                },

            },
            is_processing:false,
        }
    },
    components:{
        mdbCard,
			mdbCardImage,
			mdbCardBody,
			mdbCardTitle,
			mdbCardText,
            mdbBtn,
            mdbListGroup, 
        mdbListGroupItem,
        mdbTabs
    },
    props:{
        user_id:String,
    },
    methods:{
        async fetchUserDetail(){
                this.moreButtonStat.work.isActive = false;
                this.moreButtonStat.work.sentence = 'Loading...';

                this.moreButtonStat.timeline.isActive = false;
                this.moreButtonStat.timeline.sentence = 'Loading...';


                const response = await axios.get(`/api/users/${this.user_id}?timelinepage=${this.pagination.timeline}&workpage=${this.pagination.work}`)
        
                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }
                if(this.buttonMore.timeline){
                    this.userInfo.timelines.push(response.data.timelines.data)

                }
                if(this.buttonMore.work){
                    this.userInfo.works.push(response.data.works.data);
                }
                this.userInfo.is_following = response.data.is_following;
                this.userInfo.its_me = response.data.its_me;
                this.userInfo.user = response.data.user;
                // this.currentPage = response.data.current_page
                // this.lastPage = response.data.last_page
                

         
                console.log(response.data)


                this.buttonMore.work =false;
                this.buttonMore.timeline =false;

                this.moreButtonStat.work.isActive = true;
                this.moreButtonStat.work.sentence = 'More';

                this.moreButtonStat.timeline.isActive = true;
                this.moreButtonStat.timeline.sentence = 'More';

                if(response.data.works.current_page >= response.data.works.last_page){
                    this.moreButtonStat.work.isActive = false;
                }

                if(response.data.timelines.current_page >= response.data.timelines.last_page){
                    this.moreButtonStat.timeline.isActive = false;
                }

                

        },
        async follow(){
            this.is_processing = true;
            const response = await axios.post(`/api/users/${this.user_id}/follow`);
            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status);
                return false;
            }else{
                this.userInfo.is_following = true;
                this.userInfo.user.followers_count += 1;
            }
            this.is_processing = false;

        
        },
        async unFollow(){
            this.is_processing = true;
            const response = await axios.delete(`/api/users/${this.user_id}/unfollow`);
            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status);
                return false;
            }else{
                this.userInfo.is_following = false;
                this.userInfo.user.followers_count -= 1;
            }
            this.is_processing = false;

        },
        moreWork(){
            
            this.pagination.work += 1;
            this.buttonMore.work =true;
            

            this.fetchUserDetail();
            
        },
        moreTimeline(){
            this.pagination.timeline += 1;
            this.buttonMore.timeline =true;
            this.fetchUserDetail();

        }

    },
    computed: {
        isLogin () {
            return this.$store.getters['auth/check']
        },
        username () {
            return this.$store.getters['auth/username']
        }
    },
    watch: {
            $route: {
            async handler () {
                await this.fetchUserDetail()
            },
            immediate: true
        }
    },

}
</script>

<style scoped>

</style>