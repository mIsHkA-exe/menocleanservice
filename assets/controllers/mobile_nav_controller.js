import { Controller } from '@hotwired/stimulus';

/*
 * Ouvre et ferme le menu de navigation mobile.
 * Se referme aussi quand la fenêtre passe au format bureau (> 920 px, même
 * point de rupture que le CSS) : sinon le défilement de la page resterait bloqué.
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

        this.bureau = window.matchMedia('(min-width: 921px)');
        this.onBreakpoint = () => {
            if (this.bureau.matches) {
                this.close();
            }
        };
        this.bureau.addEventListener('change', this.onBreakpoint);
    }

    disconnect() {
        window.removeEventListener('keydown', this.onKeydown);
        this.bureau.removeEventListener('change', this.onBreakpoint);
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