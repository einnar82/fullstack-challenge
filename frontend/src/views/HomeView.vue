<script setup lang="ts">
import {RouterView} from "vue-router";
import {onMounted} from 'vue'
import {initDrawers} from 'flowbite';
import {useUsers} from "@/store/users";
import Sidebar from "@/components/Sidebar.vue";

const store = useUsers();

onMounted(() => {
  initDrawers();
  store.fetchUsers();
})
</script>

<template>
  <main>
    <Sidebar>
      <RouterView></RouterView>
    </Sidebar>
  </main>
</template>
