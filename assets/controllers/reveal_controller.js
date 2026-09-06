import { Controller } from '@hotwired/stimulus';

/*
 * Fait apparaître les éléments .reveal à mesure qu'ils entrent dans l'écran.
 *
 * Amélioration progressive : le CSS affiche le contenu par défaut.
 * C'est ce contrôleur qui le masque ("arme" l'animation), et uniquement
 * si le navigateur sait observer l'intersection. Sans JavaScript,
 * ou si l'utilisateur a demandé moins d'animations, tout reste visible.
 */
export default class extends Controller {
    connect() {
        const elements = Array.from(this.element.querySelectorAll('.reveal'));

        if (elements.length === 0) {
            return;
        }

        const mouvementReduit = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (mouvementReduit || !('IntersectionObserver' in window)) {
            return;
        }

        elements.forEach((element) => element.classList.add('is-armed'));

        this.observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        this.observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.15, rootMargin: '0px 0px -40px 0px' }
        );

        elements.forEach((element) => this.observer.observe(element));
    }

    disconnect() {
        if (this.observer) {
            this.observer.disconnect();
            this.observer = null;
        }
    }
}