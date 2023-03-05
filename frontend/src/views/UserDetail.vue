<template>
  <div class="max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
    <a href="javascript:;">
      <img class="rounded-t-lg" src="https://random.imagecdn.app/500/250" alt="" />
    </a>
    <div class="p-5">
      <a href="javascript:;">
        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ user.name }}</h5>
      </a>
      <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Email: {{user.email}}</p>
      <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Latitude: {{user.latitude}}</p>
      <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Longitude: {{user.longitude}}</p>
      <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Weather: {{user.weather.weather[0].main}}</p>
      <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Weather Description: {{user.weather.weather[0].description}}</p>
      <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Temperature: {{user.weather.main.temp}} Celcius</p>
      <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Humidity: {{user.weather.main.humidity}} %</p>
      <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Sea Level: {{user.weather.main.humidity}} hPa</p>
      <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Ground Level: {{user.weather.main.grnd_level}} hPa</p>
      <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Wind speed: {{user.weather.wind.speed}} meter/sec</p>
      <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Wind direction: {{user.weather.wind.deg}}</p>
      <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Wind gust: {{user.weather.wind.gust}} meter/sec</p>
      <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Country code: {{user.weather.sys.country }}</p>
      <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Time of data calculation: {{ getDataCalculation(user.weather.dt) }}</p>
    </div>
  </div>

</template>

<script setup lang="ts">
import {storeToRefs} from "pinia";
import {computed, onActivated, onMounted, onServerPrefetch} from "vue";
import {useRoute} from "vue-router";
import {useUsers} from "@/store/users";

const store = useUsers();
const route = useRoute();

const getDataCalculation = (unix: number) => {
  return new Date(unix * 1000).toLocaleString();
}

const user = computed(() => store.getUser(Number(route.params.id)))
</script>
<style scoped>

</style>