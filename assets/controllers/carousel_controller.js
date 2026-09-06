import { Controller } from '@hotwired/stimulus';

/*
 * Carrousel de témoignages : flèches, pastilles, défilement automatique,
 * balayage tactile, et pause au survol.
 */
export default class extends Controller {
    static targets = ['slides', 'slide', 'dot'];
    static values = {
        index: { type: Number, default: 0 },
        interval: { type: Number, default: 6500 },
    };

    connect() {
        this.startX = null;
        this.render();
        this.start();
    }

    disconnect() {
        this.stop();
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
        this.start();
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

    start() {
        this.stop();
        this.timer = window.setInterval(() => this.aller(this.indexValue + 1), this.intervalValue);
    }

    stop() {
        if (this.timer) {
            window.clearInterval(this.timer);
            this.timer = null;
        }
    }

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