import { Controller } from '@hotwired/stimulus';

/*
 * Ouvre et ferme le menu de navigation mobile.
 */
export default class extends Controller {
    static targets = ['toggle', 'panel'];

    connect() {
        this.onKeydown = (event) => {
            if (event.key === 'Escape') {
                this.close();
            }
        };
        window.addEventListener('keydown', this.onKeydown);
    }

    disconnect() {
        window.removeEventListener('keydown', this.onKeydown);
        this.close();
    }

    toggle() {
        this.isOpen ? this.close() : this.open();
    }

    open() {
        if (!this.hasToggleTarget || !this.hasPanelTarget) {
            return;
        }
        this.toggleTarget.setAttribute('aria-expanded', 'true');
        this.panelTarget.classList.add('is-open');
        document.body.classList.add('has-nav-open');
    }

    close() {
        if (!this.hasToggleTarget || !this.hasPanelTarget) {
            return;
        }
        this.toggleTarget.setAttribute('aria-expanded', 'false');
        this.panelTarget.classList.remove('is-open');
        document.body.classList.remove('has-nav-open');
    }

    get isOpen() {
        return this.hasToggleTarget && this.toggleTarget.getAttribute('aria-expanded') === 'true';
    }
}