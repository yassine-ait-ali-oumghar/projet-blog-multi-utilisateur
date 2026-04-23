<template>
  <div class="min-h-screen bg-slate-50 flex items-center justify-center p-4 relative overflow-hidden">
      <!-- Background Decorations -->
      <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
          <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-gradient-to-br from-primary-200/40 to-purple-200/40 blur-3xl mix-blend-multiply animate-float" style="animation-duration: 10s;"></div>
          <div class="absolute top-[20%] -right-[10%] w-[40%] h-[40%] rounded-full bg-gradient-to-bl from-blue-200/40 to-cyan-200/40 blur-3xl mix-blend-multiply animate-float" style="animation-duration: 8s; animation-delay: 2s;"></div>
          <div class="absolute -bottom-[20%] left-[20%] w-[60%] h-[60%] rounded-full bg-gradient-to-tr from-emerald-200/30 to-teal-200/30 blur-3xl mix-blend-multiply animate-float" style="animation-duration: 12s; animation-delay: 1s;"></div>
      </div>

      <div class="w-full max-w-md relative z-10">
           <!-- Render Auth Page Component -->
           <router-view v-slot="{ Component }">
              <transition name="slide-fade" mode="out-in">
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
