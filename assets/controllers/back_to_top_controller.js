import { Controller } from '@hotwired/stimulus';

/*
 * Affiche le bouton de retour en haut après un certain défilement.
 * Masqué, il est aussi retiré de la navigation au clavier (visibility en CSS).
 */
export default class extends Controller {
    static values = {
        offset: { type: Number, default: 700 },
    };

    connect() {
        this.onScroll = () => this.update();
        this.update();
        window.addEventListener('scroll', this.onScroll, { passive: true });
    }

    disconnect() {
        window.removeEventListener('scroll', this.onScroll);
    }

    update() {
        this.element.classList.toggle('is-visible', window.scrollY > this.offsetValue);
    }

    scrollToTop() {
        // Pas de "behavior" ici : le défilement suit le CSS (scroll-behavior sur html),
        // doux par défaut et immédiat en mouvement réduit (C12).
        window.scrollTo({ top: 0 });
    }
}