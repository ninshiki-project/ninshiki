<script setup>
import {ref} from "vue";
import {router, usePage} from "@inertiajs/vue3";
import Menu from 'primevue/menu';


const page = usePage()
const items = ref([
  {
    separator: true
  },
  {
    label: '',
    items: [
      {
        label: 'Feed',
        icon: 'pi pi-home',
        shortcut: '⌘+F',
        command: () => {
          router.visit(route('feed'), {
            only: ['posts'],
          })
        }
      },
      {
        label: 'Logout',
        icon: 'pi pi-sign-out',
        shortcut: '⌘+Q',
      }
    ]
  },
  {
    separator: true
  }
]);
</script>

<template>
  <div class="flex">
    <main class="flex px-28 justify-center pt-10 ">
      <div class="h-screen sticky top-10">
        <!-- Fixed Sidebar -->
        <div class="card flex justify-center">
          <Menu :model="items" class="w-full md:w-60">
            <template #start>
                <span class="inline-flex items-center gap-1 px-2 py-2">
                    <span class="text-xl font-semibold text-primary">
                      {{ page.props.app.name }}
                    </span>
                </span>
            </template>
            <template #submenulabel="{ item }">
              <span class="text-primary font-bold">{{ item.label }}</span>
            </template>
            <template #item="{ item, props }">
              <a v-ripple class="flex items-center" v-bind="props.action">
                <span :class="item.icon"/>
                <span>{{ item.label }}</span>
                <Badge v-if="item.badge" class="ml-auto" :value="item.badge"/>
                <span v-if="item.shortcut"
                      class="ml-auto border border-surface rounded bg-emphasis text-muted-color text-xs p-1">{{
                    item.shortcut
                  }}</span>
              </a>
            </template>
            <template #end>
              <button v-ripple
                      class="relative overflow-hidden w-full border-0 bg-transparent flex items-start p-2 pl-4 hover:bg-surface-100 dark:hover:bg-surface-800 rounded-none cursor-pointer transition-colors duration-200">
                <Avatar
                    :image="page.props.auth.user.avatar ?? `https://avatar.iran.liara.run/username?username=${page.props.auth.user.email}`"
                    class="mr-2" shape="circle"/>
                <span class="inline-flex flex-col items-start">
                        <span class="text-balance font-bold">{{ page.props.auth.user.name }}</span>
                        <span class="text-xs font-normal italic text-gray-400">{{ page.props.auth.user.email }}</span>
                </span>
              </button>
              <p class="flex flex-row-reverse italic w-full p-2 font-normal text-sm text-gray-300">
                {{ page.props.app.version }}
              </p>
            </template>
          </Menu>

        </div>
      </div>

      <div>
        <div class="pl-5">
          <slot/>
        </div>
      </div>
    </main>
  </div>
</template>

<style scoped>

</style>
