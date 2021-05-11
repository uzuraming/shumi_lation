<template>
  <!-- ページネーションボタン -->
  <div class="pagination">
    <RouterLink
      v-if="! isFirstPage"
      :to="`/${component}/?page=${currentPage - 1}`"
      class="mr-2 btn btn-dark text-white shadow-none"
    >&laquo;</RouterLink>
    <RouterLink
      v-if="! isLastPage"
      :to="`/${component}/?page=${currentPage + 1}`"
      class="btn btn-dark text-white shadow-none"
    >&raquo;</RouterLink>
  </div>
</template>

<script>
import { mdbPagination, mdbPageItem, mdbPageNav } from 'mdbvue';
export default {
    components: {
      mdbPagination,
      mdbPageItem,
      mdbPageNav
    },
  props: {
    // 今のページ
    currentPage: {
      type: Number,
      required: true
    },
    // 最後のページ
    lastPage: {
      type: Number,
      required: true
    },
    component: {
      type: String,
      required: true
    },
  },
  computed: {
    // 最初のページであるか
    isFirstPage () {
      return this.currentPage === 1
    },
    // 最後のページであるか
    isLastPage () {
      return this.currentPage === this.lastPage
    }
  }
}
</script>