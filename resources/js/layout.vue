<template>
  <div>
    <nav-bar v-if="user!=null"></nav-bar>
    <router-view></router-view>
  </div>
</template>

<script>
export default {
  computed: {
    user: function() {
      return this.$store.state.user;
    }
  }
};
</script>