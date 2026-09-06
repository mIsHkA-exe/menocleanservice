import { Controller } from '@hotwired/stimulus';

/*
 * Ajoute un fond opaque au header dès que la page défile.
 */
export default class extends Controller {
    static values = {
        offset: { type: Number, default: 12 },
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
        this.element.classList.toggle('is-scrolled', window.scrollY > this.offsetValue);
    }
}