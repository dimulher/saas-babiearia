import './bootstrap';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import '@fortawesome/fontawesome-free/css/all.min.css';
import * as Turbo from '@hotwired/turbo';

window.Turbo = Turbo;

Alpine.plugin(collapse);
window.Alpine = Alpine;
Alpine.start();
