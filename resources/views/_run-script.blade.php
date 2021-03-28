@if(session()->has('scripts::cannot_run'))
    <input type="hidden" id="script_cannot_run" value="{{ session()->get('scripts::cannot_run') }}">
@endif
@include('scripts::_modal')
<script type="text/javascript">

    let canNotRun = document.getElementById('script_cannot_run');
    if (canNotRun) {
        alert(canNotRun.value);
    }

    let el = document.getElementById('run_script');
    el.addEventListener('click', function(event){
        event.preventDefault();
        let scriptName = document.getElementById('script_name').value;
        document.getElementById('modal_title').innerText = scriptName;
        document.getElementById('script').value = scriptName;
        const basedPath = document.getElementById('base_path').value;

        fetch(basedPath + "/dependencies/" + scriptName)
            .then(response => response.json())
            .then(data => {
                let fields = '';
                Object.keys(data.dependencies).forEach(function (dependency){
                    dependency = data.dependencies[dependency];
                    fields += getField(
                        dependency.name,
                        dependency.label,
                        dependency.description,
                        dependency.is_optional,
                    )
                });

                if (data.error || !Object.keys(data.dependencies).length) {
                    fields = 'No Dependencies found.';
                }

                document.getElementById('run_script_form_content').innerHTML = fields;
            });
        toggleModal();
    });

    const overlay = document.querySelector('.modal-overlay')
    overlay.addEventListener('click', toggleModal)

    let closeModal = document.querySelectorAll('.modal-close')
    for (let i = 0; i < closeModal.length; i++) {
        closeModal[i].addEventListener('click', toggleModal)
    }

    document.onkeydown = function(evt) {
        evt = evt || window.event
        let isEscape = false
        if ("key" in evt) {
            isEscape = (evt.key === "Escape" || evt.key === "Esc")
        } else {
            isEscape = (evt.keyCode === 27)
        }
        if (isEscape && document.body.classList.contains('modal-active')) {
            toggleModal()
        }
    };

    document.getElementById('run_script_btn').addEventListener('click', function (){
        const basedPath = document.getElementById('base_path').value;
        let scriptName = document.getElementById('script_name').value;
        let form = document.getElementById('run_script_form');
        let formBtn = document.getElementById('run_script_form_btn');
        form.action = basedPath + '/run/' + scriptName;
        formBtn.click();
    });

    function toggleModal () {
        const body = document.querySelector('body')
        const modal = document.querySelector('.modal')
        modal.classList.toggle('opacity-0')
        modal.classList.toggle('pointer-events-none')
        body.classList.toggle('modal-active')
    }

    function getField(name, label, description, isOptional)
    {
        return '<div class="mb-4">' +
            '<label class="block text-gray-700 text-sm font-bold mb-2" for="'+ name +'">' +
            label + (!isOptional ? ' *' : '') +
            '</label>' +
            '<input'+(!isOptional ? ' required' : '')+' class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="'+ name +'" type="text" placeholder="'+ description +'">' +
            '</div>'
    }
</script>
