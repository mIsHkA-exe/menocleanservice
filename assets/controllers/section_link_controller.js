import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    suivre(event) {
        if (event.defaultPrevented || event.button !== 0
            || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
            return;
        }

        const lien = event.target.closest('a[href]');

        if (!lien || (lien.target && lien.target !== '_self') || lien.hasAttribute('download')) {
            return;
        }

        const url = new URL(lien.href, window.location.href);

        if (url.origin !== window.location.origin || url.pathname !== window.location.pathname) {
            return;
        }

        const id = decodeURIComponent(url.hash.slice(1));

        // Lien vers la page sans ancre (ex. logo) : retour en haut.
        if (id === '') {
            event.preventDefault();
            window.scrollTo({ top: 0 });
            this.focaliser(document.getElementById('contenu'));
            return;
        }

        const section = document.getElementById(id);

        if (!section) {
            return;
        }

        event.preventDefault();

        const categorie = lien.dataset.sectionLinkCategorie;

        if (categorie) {
            section.dispatchEvent(new CustomEvent('filter:selectionner', {
                detail: { valeur: categorie },
            }));
        } else {
            this.focaliser(section);
        }

        section.scrollIntoView({ block: 'start' });
    }

    // Déplace le focus clavier sans faire défiler (le défilement est géré à part).
    focaliser(element) {
        if (!element) {
            return;
        }

        if (!element.hasAttribute('tabindex')) {
            element.setAttribute('tabindex', '-1');
        }

        element.focus({ preventScroll: true });
    }
}
