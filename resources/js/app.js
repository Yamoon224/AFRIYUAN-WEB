import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.store('theme', {
    dark: localStorage.getItem('afriyuan_theme') === 'dark',

    init() {
        this.applyClass();
    },

    toggle() {
        this.dark = !this.dark;
        localStorage.setItem('afriyuan_theme', this.dark ? 'dark' : 'light');
        this.applyClass();
    },

    applyClass() {
        document.documentElement.classList.toggle('dark', this.dark);
    },
});

Alpine.start();
