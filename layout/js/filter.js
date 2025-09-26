document.addEventListener('DOMContentLoaded', () => {
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
    })
})