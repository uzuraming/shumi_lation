<template>
  <div>

      <mdb-card class="card-body shadow-none" style="width: 22rem; margin-top: 1rem;">
        <mdb-card-title class="text-center">
          404
      </mdb-card-title>
        <mdb-card-text class="text-center">
            <p>ページが存在しません。</p>
            <button class="btn btn-primary shadow-none" @click="$router.push({path:'/'})">
                ホームに戻る
            </button>
        </mdb-card-text>
        
    </mdb-card>
      
      
  </div>
</template>

<script>
import { mdbCard, mdbCardTitle, mdbCardText, mdbBtn } from 'mdbvue';

export default {
    components: {
      mdbCard,
      mdbCardTitle,
      mdbCardText,
    }

}
</script>

<style>

</style>