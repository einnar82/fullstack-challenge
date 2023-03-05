import { defineStore } from "pinia";
import type { User } from "@/types";

export const useUsers = defineStore('users',
    {
      state: () => ({
        users: [] as User[],
      }),
      getters: {
        getUser: (state) => {
          return (userId: number) => state.users.find(item => item.id === userId)
        },
        getUsers: (state) => state.users,
      },
      actions: {
        async fetchUsers() {
          const url = 'http://localhost/'
          const response = await (await fetch(url)).json();
          this.users = response.users;
        },
      }
    })
