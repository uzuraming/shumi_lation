<template>
    
    <div>
        <!-- ロード中 -->
        <div class="d-flex justify-content-center align-center m-5"  v-if="isLoad">
            <div class="spinner-grow spinner" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>

        
        <div class="" ref="a" v-if="!isLoad">
            <!-- チャット -->
            <div class="mb-5 pb-5">  
                <div v-for="(message, index) in messages" :key="index">
                    <!-- メッセージの送信が自分のIDと一致した場合吹き出しが右に -->
                    <div :class="{'d-flex justify-content-end' : message.id == your_id}">
                        <div :class="{'balloon1_right' : message.id == your_id, 'balloon1_left' : message.id != your_id}" >
                            <p>{{ message.pivot.message }}</p>
                            <small>{{message.pivot.created_at}}</small>
                            </div>
                    </div>
                </div>
            </div>
            

            <!-- チャット送信フォーム -->
            <form class="form-inline mb-5 row fixed-bottom white d-flex justify-content-center shadow">
                <input class="form-control m-1 col-8" type="text" v-model="new_message">
                <button :disabled="new_message.length<=0" @click="postChat" class="btn shadow-none btn-outline-success mx-1 col-2 px-1" type="button">送信</button>
            </form>
        </div>
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
            chat_user:[],
            pagination:{
                page:1
            },
           
            moreButtonStat:{
                isActive:true,
            },
            isLoad:true,

        }
    },
    components: {
            mdbIcon,  
    },
    props:{
        chat_room_id:String,
    },
    methods:{
        // チャットを取得
        async fetchChat(){
            
            const response = await axios.get(`/api/chats/${this.chat_room_id}`);
            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }

            console.log(response)
            this.messages = response.data.messages;
            this.your_id = response.data.your_id
            this.chat_user =  response.data.chat_user
            console.log(this.chat_user)
            this.$store.dispatch('chat_user/changeChatUser', this.chat_user.name)
  
        },
        // チャットを送信
        async postChat(){
            const response = await axios.post(`/api/chats/${this.chat_room_id}`, { 'message': this.new_message });
            this.new_message = "";
            console.log(response.status);
       

        },
        
    },
    watch: {
        $route: {
        async handler () {
            await this.fetchChat();
            this.isLoad = false;
        },
        immediate: true
        }
    },
    updated(){
        const chatLog = this.$refs.a;
        console.log(chatLog.scrollHeight)
        
            window.scroll({
                top: chatLog.scrollHeight,
                behavior: "instant"
            });

    },
    mounted() {
        // クッキーの文字列からトークンを抽出
        const name = 'XSRF-TOKEN'
        const cookies = '; ' + document.cookie
        const parts = cookies.split('; ' + name + '=')
        let value = parts.length == 2 ? parts.pop().split(';').shift() : null
        value = decodeURIComponent(value)

        // pusherにトークンを添付
        Echo.connector.pusher.config.auth.headers['X-XSRF-TOKEN'] = value

        // Laravelのブロードキャストのプライベートチャンネルの発火をリッスンしたらメッセージを再読込する
        Echo.private(`chat.${this.chat_room_id}`)
        .listen('MessageCreated', (e) => {
            this.fetchChat(); // 全メッセージを再読込
        });

      
    }


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

