import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static values = {
        position: { type: Number, default: 50 },
        step: { type: Number, default: 5 },
    };

    connect() {
        this.dragging = false;

        this.element.setAttribute('role', 'slider');
        this.element.setAttribute('tabindex', '0');
        this.element.setAttribute('aria-label', 'Comparateur avant après, faites glisser pour comparer');
        this.element.setAttribute('aria-valuemin', '0');
        this.element.setAttribute('aria-valuemax', '100');

        this.appliquer(this.positionValue);
    }

    start(event) {
        this.dragging = true;
        this.element.setPointerCapture(event.pointerId);
        this.depuisX(event.clientX);
    }

    move(event) {
        if (!this.dragging) {
            return;
        }
        this.depuisX(event.clientX);
    }

    stop() {
        this.dragging = false;
    }

    keyboard(event) {
        if (event.key === 'ArrowLeft') {
            event.preventDefault();
            this.appliquer(this.positionValue - this.stepValue);
        }

        if (event.key === 'ArrowRight') {
            event.preventDefault();
            this.appliquer(this.positionValue + this.stepValue);
        }
    }

    depuisX(clientX) {
        const rect = this.element.getBoundingClientRect();
        this.appliquer(((clientX - rect.left) / rect.width) * 100);
    }

    appliquer(pourcentage) {
        const position = Math.min(Math.max(pourcentage, 2), 98);

        this.positionValue = position;
        this.element.style.setProperty('--compare-pos', `${position}%`);
        this.element.setAttribute('aria-valuenow', Math.round(position));
    }
}