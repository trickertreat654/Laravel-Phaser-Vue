<script setup>
import Phaser from 'phaser';
import { ref, toRaw, watch } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import PhaserGame from './game/PhaserGame.vue';
import { usePoll } from '@inertiajs/vue3'
// import { useRouter } from 'vue-router'

// usePoll(2000)


defineProps({
    gameinfo: String,
    scores: Array,
    number: Number,
})

const page = usePage();


// The sprite can only be moved in the MainMenu Scene
const canMoveSprite = ref();

//  References to the PhaserGame component (game and scene are exposed)
const phaserRef = ref();
const spritePosition = ref({ x: 0, y: 0 });

const changeScene = () => {

    const scene = toRaw(phaserRef.value.scene);

    if (scene)
    {
        //  Call the changeScene method defined in the `MainMenu`, `Game` and `GameOver` Scenes
        scene.changeScene();
    }

}

const handlePlayerLost = (score) => {
    router.put(route('trash.update', {score: score, name: page.props.auth.user.name}));
};

const moveSprite = () => {

    const scene = toRaw(phaserRef.value.scene);

    if (scene)
    {
        //  Call the `moveLogo` method in the `MainMenu` Scene and capture the sprite position
        scene.moveLogo(({ x, y }) => {

            spritePosition.value = { x, y };

        });
    }

}

const updateRandom = (data) => {
    router.reload({data: {spot: data}, only: ['number'], onFinish: visit => {
        router.replace({
        url: '/dashboard', // Use the current path without query parameters
        preserveState: true, // Keep the page's current state
    });
    },});
   

}
console.log(page.props.auth.user.name);

const addSprite = () => {

    const scene = toRaw(phaserRef.value.scene);

    if (scene)
    {
        //  Add a new sprite to the current scene at a random position
        const x = Phaser.Math.Between(64, scene.scale.width - 64);
        const y = Phaser.Math.Between(64, scene.scale.height - 64);

        //  `add.sprite` is a Phaser GameObjectFactory method and it returns a Sprite Game Object instance
        const star = scene.add.sprite(x, y, 'star');

        //  ... which you can then act upon. Here we create a Phaser Tween to fade the star sprite in and out.
        //  You could, of course, do this from within the Phaser Scene code, but this is just an example
        //  showing that Phaser objects and systems can be acted upon from outside of Phaser itself.
        scene.add.tween({
            targets: star,
            duration: 500 + Math.random() * 1000,
            alpha: 0,
            yoyo: true,
            repeat: -1
        });
    }

}

//  This event is emitted from the PhaserGame component:
const currentScene = (scene) => {

    canMoveSprite.value = (scene.scene.key !== 'MainMenu');

}
</script>

<template>
    <div class="flex bg-black  justify-center items-center h-screen">
        <PhaserGame :number @player-position="updateRandom" @player-lost="handlePlayerLost" :gameinfo="page.props.auth.user.name" ref="phaserRef" @current-active-scene="currentScene" />
        
        <div>
            <div>
                <button class="button" @click="changeScene">{{gameinfo}}Change Scene</button>
            </div>
            <div>
                <Link method='put' class="button" :href="route('trash.update')">Change Name</Link>
            </div>
            <div>
                <button :disabled="canMoveSprite" class="button" @click="moveSprite">Toggle Movement</button>
            </div>
            <div>
                <button class="button" @click="addSprite">Add New Sprite</button>
            </div>
            <!-- {{ scores }} -->
           <div  v-for="score in scores" class="text-white">
                <p>{{score.name}}: {{score.my_score}}</p>
           </div>
        </div>
    </div>
    
</template>
