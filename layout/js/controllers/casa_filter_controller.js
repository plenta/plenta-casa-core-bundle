import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = [ 'type' ];
    timer;
    keyupDelay = 1000;
    onChangeDelay = 1000;

    filter(event) {
        if (event.target.type === 'text') {
            return;
        }

        let filter = this.element;
        clearTimeout(this.timer);
        this.timer = setTimeout(this.processFilter, this.onChangeDelay, filter);
    }

    keyUp(event) {
        if (event.target.type !== 'text') {
            return;
        }

        let filter = this.element;
        clearTimeout(this.timer);
        this.timer = setTimeout(this.processFilter, this.keyupDelay, filter);
    }

    processFilter(filter) {
        let formData = new FormData(filter.querySelector('form'));
        let params = new URLSearchParams(formData);
        let list = document.querySelector('.content-plenta-casa-listing');

        let event = new CustomEvent('casa_filterChanged');
        event.searchParams = params;
        document.dispatchEvent(event);

        let string = params.toString();
        if (string) {
            string = '?' + string;
        }

        history.replaceState(null, null, location.pathname + string);

        fetch ('_casa/filter/' + list.dataset.id + string, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
        }).then(r => r.text()).then(r => {
            list.outerHTML = r.replaceAll('/_casa/filter/' + list.dataset.id, location.pathname);
        })
    }
}