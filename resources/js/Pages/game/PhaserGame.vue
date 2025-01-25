<script setup>
import { onMounted, watch, onUnmounted, ref } from 'vue';
import { EventBus } from './EventBus';
import StartGame from './main';
import { usePage } from '@inertiajs/vue3';

// Save the current scene instance
const scene = ref();
const game = ref();

const props = defineProps({
    gameinfo: String,
    number: Number,
});
// console.log(props.gameinfo);

const page = usePage();

watch (() => props.number, (data) => {
    EventBus.emit('event-name', data);
});

const emit = defineEmits(['current-active-scene', 'player-lost']);

onMounted(() => {

    game.value = StartGame('game-container', props.gameinfo);

    EventBus.on('current-scene-ready', (currentScene) => {

        emit('current-active-scene', currentScene);

        scene.value = currentScene;

    });

     // Listen for the player-lost event
     EventBus.on('player-lost', (payload) => {
        emit('player-lost', payload.score); // Emit to parent
    });

    // Listen for the player-position event
    EventBus.on('player-position', (payload) => {
        emit('player-position', payload); // Emit to parent
    });

});

onUnmounted(() => {

    if (game.value)
    {
        game.value.destroy(true);
        game.value = null;
    }
    
});

defineExpose({ scene, game });
</script>

<template>
 
    
    <div id="game-container"></div>
</template>