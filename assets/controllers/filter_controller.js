import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['chip', 'item'];
    static values = {
        initial: { type: String, default: '' },
    };

    connect() {
        if (this.initialValue) {
            this.filtrer(this.initialValue);
        }
    }

    apply(event) {
        this.filtrer(event.params.value);
    }

    selectionner(event) {
        const chip = this.filtrer(event.detail?.valeur);

        // Le focus clavier suit le lien : il repart du filtre activé.
        chip?.focus({ preventScroll: true });
    }

    filtrer(valeur) {
        const actif = this.chipTargets.find((chip) => chip.dataset.filterValueParam === valeur);

        if (!actif) {
            return null;
        }

        this.chipTargets.forEach((chip) => {
            chip.classList.toggle('is-active', chip === actif);
        });

        this.itemTargets.forEach((item) => {
            const categories = (item.dataset.category || '').split(' ');
            item.hidden = valeur !== 'tous' && !categories.includes(valeur);
        });

        return actif;
    }
}
