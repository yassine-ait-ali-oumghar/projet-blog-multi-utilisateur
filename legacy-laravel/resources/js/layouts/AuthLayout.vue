<template>
  <div class="min-h-screen bg-slate-100 flex items-center justify-center p-4">
      <div class="w-full max-w-md">
           <!-- Render Auth Page Component -->
           <router-view v-slot="{ Component }">
              <transition name="fade" mode="out-in">
                <component :is="Component" />
              </transition>
            </router-view>
      </div>
  </div>
</template>

<script>
export default {
    name: 'AuthLayout'
}
</script>
