document.addEventListener('DOMContentLoaded', () => {
    const keyupDelay = 1000;
    const onChangeDelay = 1000;
    let timer;

    let filter = document.querySelectorAll('.content-plenta-casa-filter');

    filter.forEach(filter => {
        if (filter.classList.contains('has-types')) {
            let objectType = filter.querySelector('select[name*="objectType"]');
            let type = filter.querySelector('select[name*="[type]"]');

            objectType.addEventListener('change', (e) => {
                type.querySelectorAll('option:not([value=""])').forEach(o => {
                    o.remove();
                })

                if (objectType.value !== '') {
                    fetch('_casa/types/' + objectType.value).then(r => r.json()).then(r => {
                        for (let i in r) {
                            let option = document.createElement('option');
                            option.value = i;
                            option.innerText = r[i];
                            type.appendChild(option);
                        }
                    })
                }

                document.dispatchEvent(new CustomEvent('casa_typeOptionsUpdated'));
            })
        }

        filter.addEventListener('change', function (e) {
            if (e.target.type === 'text') {
                return;
            }

            clearTimeout(timer);
            timer = setTimeout(processFilter, onChangeDelay, filter);
        })

        let textInputs = filter.querySelectorAll('input[type="text"]');
        textInputs.forEach(input => {

            input.addEventListener('keyup', function () {
                clearTimeout(timer);
                timer = setTimeout(processFilter, keyupDelay, filter);
            })
        })
    })

    function processFilter(filter) {
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
})