import { Controller } from '@hotwired/stimulus';
import { getComponent } from '@symfony/ux-live-component';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    component = null;

    async initialize() {
        this.component = await getComponent(this.element.querySelector('div'));
    }

    connect() {
        document.addEventListener('casa_renderFilter', () => {
            this.component.render();
        });
    }
}
