import { Controller } from '@hotwired/stimulus';

/*
 * Accordéon FAQ : une seule question ouverte à la fois.
 * Le contrôleur ne fait que poser une classe — l'animation est en CSS.
 */
export default class extends Controller {
    static targets = ['item'];

    toggle(event) {
        const item = event.currentTarget.closest('[data-accordion-target="item"]');

        if (!item) {
            return;
        }

        const etaitOuvert = item.classList.contains('is-open');

        this.closeAll();

        if (!etaitOuvert) {
            item.classList.add('is-open');
            this.bouton(item)?.setAttribute('aria-expanded', 'true');
        }
    }

    closeAll() {
        this.itemTargets.forEach((item) => {
            item.classList.remove('is-open');
            this.bouton(item)?.setAttribute('aria-expanded', 'false');
        });
    }

    bouton(item) {
        return item.querySelector('.faq-q');
    }
}