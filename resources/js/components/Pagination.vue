<template>
  <div class="pagination">
    <RouterLink
      v-if="! isFirstPage"
      :to="`/${component}/?page=${currentPage - 1}`"
      class="mr-2 btn btn-dark text-white"
    >&laquo;</RouterLink>
    <RouterLink
      v-if="! isLastPage"
      :to="`/${component}/?page=${currentPage + 1}`"
      class="btn btn-dark text-white"
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
    currentPage: {
      type: Number,
      required: true
    },
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
    isFirstPage () {
      return this.currentPage === 1
    },
    isLastPage () {
      return this.currentPage === this.lastPage
    }
  }
}
</script>