import { Controller } from '@hotwired/stimulus';

/*
 * Carrousel de témoignages : flèches, pastilles, défilement automatique,
 * balayage tactile.
 *
 * Défilement automatique (C12) :
 *   - jamais si le visiteur a demandé moins d'animations (prefers-reduced-motion),
 *     y compris si ce réglage change pendant la visite ;
 *   - en pause tant que la souris survole le carrousel ;
 *   - en pause tant que le focus clavier est dans le carrousel (flèches, pastilles).
 * Les flèches, les pastilles et le balayage restent toujours utilisables.
 */
export default class extends Controller {
    static targets = ['slides', 'slide', 'dot'];
    static values = {
        index: { type: Number, default: 0 },
        interval: { type: Number, default: 6500 },
    };

    connect() {
        this.startX = null;
        this.survol = false;
        this.focus = false;

        this.mouvementReduit = window.matchMedia('(prefers-reduced-motion: reduce)');
        this.onMotionChange = () => this.planifier();
        this.mouvementReduit.addEventListener('change', this.onMotionChange);

        this.render();
        this.planifier();
    }

    disconnect() {
        this.arreter();
        this.mouvementReduit.removeEventListener('change', this.onMotionChange);
    }

    indexValueChanged() {
        this.render();
    }

    next() {
        this.aller(this.indexValue + 1);
    }

    previous() {
        this.aller(this.indexValue - 1);
    }

    goTo(event) {
        this.aller(event.params.index);
    }

    aller(index) {
        const total = this.slideTargets.length;

        if (total === 0) {
            return;
        }

        this.indexValue = ((index % total) + total) % total;

        // Repart pour un intervalle complet après une action du visiteur.
        this.planifier();
    }

    render() {
        if (!this.hasSlidesTarget) {
            return;
        }

        this.slidesTarget.style.setProperty('--carousel-offset', `-${this.indexValue * 100}%`);

        this.dotTargets.forEach((dot, position) => {
            const actif = position === this.indexValue;
            dot.classList.toggle('is-active', actif);
            dot.setAttribute('aria-current', actif ? 'true' : 'false');
        });
    }

    // --- Pause : actions branchées dans _testimonials.html.twig ---

    survolDebut() {
        this.survol = true;
        this.planifier();
    }

    survolFin() {
        this.survol = false;
        this.planifier();
    }

    focusEntre() {
        this.focus = true;
        this.planifier();
    }

    focusSort(event) {
        // Le focus passe d'un bouton du carrousel à un autre : on reste en pause.
        if (this.element.contains(event.relatedTarget)) {
            return;
        }

        this.focus = false;
        this.planifier();
    }

    // --- Minuterie ---

    get defilementAutorise() {
        return !this.mouvementReduit.matches && !this.survol && !this.focus;
    }

    planifier() {
        this.arreter();

        if (this.defilementAutorise) {
            this.timer = window.setInterval(() => this.aller(this.indexValue + 1), this.intervalValue);
        }
    }

    arreter() {
        if (this.timer) {
            window.clearInterval(this.timer);
            this.timer = null;
        }
    }

    // --- Balayage tactile ---

    touchStart(event) {
        this.startX = event.touches[0].clientX;
    }

    touchEnd(event) {
        if (this.startX === null) {
            return;
        }

        const delta = event.changedTouches[0].clientX - this.startX;

        if (Math.abs(delta) > 40) {
            this.aller(this.indexValue + (delta < 0 ? 1 : -1));
        }

        this.startX = null;
    }
}
