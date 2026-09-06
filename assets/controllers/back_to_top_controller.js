import { Controller } from '@hotwired/stimulus';

/*
 * Affiche le bouton de retour en haut après un certain défilement.
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
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}