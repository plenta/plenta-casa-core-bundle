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

    types(event) {
        let objectType = event.target;
        let filter = this.element;

        if (objectType.tagName === 'INPUT') {
            return;
        }

        if (this.hasTypeTarget) {
            let type = this.typeTarget;

            if (type.tagName !== 'SELECT') {
                return;
            }

            type.querySelectorAll('option:not([value=""])').forEach(o => {
                o.remove();
            })

            if (objectType.value !== '') {
                fetch('_casa/types/' + objectType.value + '/' + filter.dataset.id).then(r => r.json()).then(r => {
                    for (let i in r) {
                        let option = document.createElement('option');
                        option.value = i;
                        option.innerText = r[i];
                        type.appendChild(option);
                    }
                })
            }

            document.dispatchEvent(new CustomEvent('casa_typeOptionsUpdated'));
        }
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