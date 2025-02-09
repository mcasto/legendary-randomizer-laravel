<template>
  <div class="flex flex-col h-screen bg-gray-100">
    <!-- Toolbar with player selection buttons -->
    <div class="bg-blue-500 p-4 text-white flex justify-center">
      <button
        v-for="players in [1, 2, 3, 4, 5]"
        :key="players"
        class="mx-2 px-4 py-2 bg-blue-700 hover:bg-blue-600 rounded"
        @click="setNumPlayers(players)"
      >
        {{ players }} Players
      </button>
    </div>

    <!-- View toggles for heroes and enemies (showing in tabs) -->
    <div class="flex-grow flex">
      <!-- Mobile-first: Stack view for smaller screens -->
      <div class="w-full lg:w-1/2 p-4">
        <div class="tabs">
          <button
            :class="['tab', { active: selectedView === 'heroes' }]"
            @click="selectedView = 'heroes'"
          >
            Heroes
          </button>
          <button
            :class="['tab', { active: selectedView === 'enemies' }]"
            @click="selectedView = 'enemies'"
          >
            Enemies
          </button>
        </div>

        <!-- Conditional rendering based on selected view -->
        <HeroesComponent v-if="selectedView === 'heroes'" :heroes="heroes" />
        <EnemiesComponent v-else :enemies="enemies" />
      </div>

      <!-- On medium screens and above, show both components side-by-side -->
      <div class="hidden lg:block lg:w-1/2 p-4">
        <div class="flex">
          <div class="w-1/2">
            <HeroesComponent :heroes="heroes" />
          </div>
          <div class="w-1/2">
            <EnemiesComponent :enemies="enemies" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, watchEffect } from "vue";
import HeroesComponent from "./HeroesComponent.vue";
import EnemiesComponent from "./EnemiesComponent.vue";

export default {
  components: {
    HeroesComponent,
    EnemiesComponent,
  },
  setup() {
    const numPlayers = ref(null);
    const heroes = ref([]);
    const enemies = ref([]);
    const selectedView = ref("heroes");

    // Watch for numPlayers changes and fetch data from the backend
    watchEffect(() => {
      if (numPlayers.value) {
        // Replace with your API call
        fetch(`/api/game-setup/${numPlayers.value}`)
          .then((res) => res.json())
          .then((data) => {
            heroes.value = data.heroes;
            enemies.value = data.enemies;
          });
      }
    });

    // Set the number of players
    const setNumPlayers = (players) => {
      numPlayers.value = players;
    };

    return {
      numPlayers,
      heroes,
      enemies,
      selectedView,
      setNumPlayers,
    };
  },
};
</script>

<style scoped>
.tabs {
  display: flex;
  justify-content: space-around;
  border-bottom: 2px solid #ccc;
}

.tab {
  padding: 10px;
  cursor: pointer;
}

.tab.active {
  font-weight: bold;
  border-bottom: 2px solid #000;
}
</style>
