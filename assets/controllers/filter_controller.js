import { Controller } from '@hotwired/stimulus';

/*
 * Filtrage par catégorie. Sert à la fois aux services et à la galerie :
 * même comportement, deux usages.
 */
export default class extends Controller {
    static targets = ['chip', 'item'];

    apply(event) {
        const filtre = event.params.value;

        this.chipTargets.forEach((chip) => {
            chip.classList.toggle('is-active', chip === event.currentTarget);
        });

        this.itemTargets.forEach((item) => {
            const categories = (item.dataset.category || '').split(' ');
            item.hidden = filtre !== 'tous' && !categories.includes(filtre);
        });
    }
}