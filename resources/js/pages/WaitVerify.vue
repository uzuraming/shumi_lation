<template>
<div>
    <div>
      仮登録が終了しました。<br>
      メールアドレスを確認してください！
    </div>
    <div>
        <button @click="resend" class="btn btn-success shadow-none">再送する</button>
    </div>

</div>

</template>

<script>
export default {
    methods:{
        // 認証メールをもう一度送る
        async resend(){
            const response = await axios.post('/api/email/resend');

            console.log(response)
        }     
    },
    name:"waitVerify"
}
</script>

<style>

</style>