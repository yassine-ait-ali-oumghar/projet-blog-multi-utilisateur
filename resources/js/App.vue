<template>
  <component :is="layout">
    <router-view></router-view>
  </component>
</template>

<script>
import AdminLayout from './layouts/AdminLayout.vue';
import PublicLayout from './layouts/PublicLayout.vue';
import AuthLayout from './layouts/AuthLayout.vue';

export default {
  name: 'App',
  components: {
      AdminLayout,
      PublicLayout,
      AuthLayout
  },
  computed: {
      layout() {
          return this.$route.meta.layout || 'PublicLayout';
      }
  }
}
</script>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
