<template>
  <div>
      <div class="mb-5">
          <div @click="moreChat" class="d-flex justify-content-center">
              <mdb-icon icon="ellipsis-h" />
          </div>
          <div v-for="(message, index) in reverseItems" :key="index">
              <div v-for="(d, index) in message" :key="index">
                  <div :class="{'d-flex justify-content-end' : d.id == your_id}">
                    <div :class="{'balloon1_right' : d.id == your_id, 'balloon1_left' : d.id != your_id}" >
                        <p>{{ d.pivot.message }}</p>
                        <small>{{d.pivot.created_at}}</small>
                    </div>
                </div>

              </div>
            
        
      </div>


      </div>
      

  <form class="form-inline row fixed-bottom white d-flex justify-content-center shadow">
      <input class="form-control m-1 col-8" type="text" v-model="new_message">
      <button :disabled="new_message.length<=0" @click="postChat" class="btn shadow-none btn-outline-success mx-1 col-2 px-1" type="button">send</button>
    </form>


  </div>
</template>

<script>
import { OK } from '../util';
import { mdbIcon  } from 'mdbvue';

export default {
    data(){
        return{
            messages:[],
            your_id:null,
            new_message:"",
            pagination:{
                page:1
            },
           
            moreButtonStat:{
                isActive:true,
            },

        }
    },
    components: {
            mdbIcon,  
    },
    props:{
        chat_room_id:String,
    },
    methods:{
        async fetchChat(){
            const response = await axios.get(`/api/chats/${this.chat_room_id}?page=${this.pagination.page}`);
            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            console.log(response)
            this.messages.push(response.data.messages.data.slice().reverse());
            this.your_id = response.data.your_id
            console.log(this.messages)
        },
        async postChat(){
            const response = await axios.post(`/api/chats/${this.chat_room_id}`, { 'message': this.new_message });
            this.new_message = "";
            console.log(response.status);
            this.fetchChat();

        },
        moreChat(){
            this.moreButtonStat.isActive =false;
            this.pagination.page += 1;
            this.fetchChat();
            this.moreButtonStat.isActive =true;
        }

        
    },
    computed:{
        reverseItems() {
            return this.messages.slice().reverse();
        }
    },

    watch: {
        $route: {
        async handler () {
            await this.fetchChat();
        },
        immediate: true
        }
    },


}
</script>

<style scoped>
    .balloon1_right {
    position: relative;
    display: inline-block;
    margin: 1.5em 15px 1.5em 0;
    padding: 7px 10px;
    min-width: 50%;
    max-width: 100%;
    color: white;
    font-size: 16px;
    background: #2E2E2E;
    }

    .balloon1_right:before {
    content: "";
    position: absolute;
    top: 50%;
    left: 100%;
    margin-top: -15px;
    border: 15px solid transparent;
    border-left: 15px solid #2E2E2E;
    }

    .balloon1_right p {
    margin: 0;
    padding: 0;
    }


    .balloon1_left {
    position: relative;
    display: inline-block;
    margin: 1.5em 0 1.5em 15px;
    padding: 7px 10px;
    min-width: 50%;
    max-width: 100%;
    color: #555;
    font-size: 16px;
    background: white;
    }

    .balloon1_left:before {
    content: "";
    position: absolute;
    top: 50%;
    left: -30px;
    margin-top: -15px;
    border: 15px solid transparent;
    border-right: 15px solid white;
    }

    .balloon1_left p {
    margin: 0;
    padding: 0;
    }

</style>>

