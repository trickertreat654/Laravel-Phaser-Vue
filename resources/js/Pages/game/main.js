import { Boot } from './scenes/Boot';
import { Game } from './scenes/Game';
import { GameOver } from './scenes/GameOver';
import { MainMenu } from './scenes/MainMenu';
import Phaser from 'phaser';
import { Preloader } from './scenes/Preloader';

// Find out more information about the Game Config at:
// https://newdocs.phaser.io/docs/3.70.0/Phaser.Types.Core.GameConfig
const config = {
    type: Phaser.AUTO,
    width: 800,
    height: 600,
    // width: window.innerWidth, // Dynamically use window width
    // height: window.innerHeight, // Dynamically use window height
    parent: 'game-container',
    backgroundColor: '#028af8',
    physics: {
        default: 'arcade',
        arcade: {
            gravity: { y: 300 },
            debug: false
        }
    },
    // scale: {
    //     mode: Phaser.Scale.RESIZE, // Automatically resize the canvas
    //     autoCenter: Phaser.Scale.CENTER_BOTH, // Center the game in the window
    // },
    scene: [
        Boot,
        Preloader,
        MainMenu,
        Game,
        GameOver
    ]
};

const StartGame = (parent, gameinfo) => {

    const game = new Phaser.Game({ ...config, parent});
    game.customData = gameinfo;
    return game;
}

export default StartGame;
