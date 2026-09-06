import { Controller } from '@hotwired/stimulus';

/*
 * Écran de chargement.
 *
 * Il disparaît dès que la page est prête, jamais après un délai imposé.
 * Deux garde-fous encadrent ce comportement :
 *   - une durée MINIMUM, pour éviter un clignotement désagréable sur
 *     une connexion rapide ;
 *   - une durée MAXIMUM, pour qu'une ressource lente ne piège jamais
 *     le visiteur derrière l'écran.
 */
export default class extends Controller {
    static values = {
        minimum: { type: Number, default: 400 },
        maximum: { type: Number, default: 3000 },
    };

    connect() {
        this.debut = performance.now();
        this.masque = false;

        // Filet de sécurité : quoi qu'il arrive, l'écran finit par disparaître.
        this.secours = window.setTimeout(() => this.masquer(), this.maximumValue);

        if (document.readyState === 'complete') {
            this.programmer();
        } else {
            this.onLoad = () => this.programmer();
            window.addEventListener('load', this.onLoad, { once: true });
        }
    }

    disconnect() {
        window.clearTimeout(this.secours);
        window.clearTimeout(this.attente);

        if (this.onLoad) {
            window.removeEventListener('load', this.onLoad);
        }
    }

    programmer() {
        const ecoule = performance.now() - this.debut;
        const restant = Math.max(this.minimumValue - ecoule, 0);

        this.attente = window.setTimeout(() => this.masquer(), restant);
    }

    masquer() {
        if (this.masque) {
            return;
        }

        this.masque = true;
        window.clearTimeout(this.secours);
        this.element.classList.add('is-hidden');
    }
}