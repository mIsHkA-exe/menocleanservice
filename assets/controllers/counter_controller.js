import { Controller } from '@hotwired/stimulus';

/*
 * Compteur animé : part de zéro quand l'élément entre dans l'écran.
 *
 * Amélioration progressive : la valeur finale est écrite dans le HTML.
 * Sans JavaScript, le vrai chiffre s'affiche. Le contrôleur remet à zéro
 * puis anime, uniquement s'il peut le faire proprement.
 */
export default class extends Controller {
    static values = {
        to: Number,
        suffix: { type: String, default: '' },
        duration: { type: Number, default: 1400 },
    };

    connect() {
        this.frame = null;

        const mouvementReduit = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (mouvementReduit || !('IntersectionObserver' in window)) {
            this.afficher(this.toValue);
            return;
        }

        this.afficher(0);

        this.observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        this.animer();
                        this.observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.5 }
        );

        this.observer.observe(this.element);
    }

    disconnect() {
        if (this.observer) {
            this.observer.disconnect();
            this.observer = null;
        }

        if (this.frame) {
            window.cancelAnimationFrame(this.frame);
            this.frame = null;
        }
    }

    animer() {
        const debut = performance.now();

        const etape = (maintenant) => {
            const progression = Math.min((maintenant - debut) / this.durationValue, 1);
            const adouci = 1 - Math.pow(1 - progression, 3);

            this.afficher(Math.round(this.toValue * adouci));

            this.frame = progression < 1 ? window.requestAnimationFrame(etape) : null;
        };

        this.frame = window.requestAnimationFrame(etape);
    }

    afficher(valeur) {
        this.element.textContent = `${valeur}${this.suffixValue}`;
    }
}