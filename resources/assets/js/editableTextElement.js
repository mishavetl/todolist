export {addEditableTextElement, addEditableTextElementEvents};

function showHide(elem1, elem2)
{
    var elem1Display = elem1.style.display;
    elem1.style.display = elem2.style.display;
    elem2.style.display = elem1Display;
}

function registerEditableTextElementAddBtn(elems, snippet, namespace, btn, )
{
    
}

function addEditableTextElement(elems, snippet, namespace, id, name)
{
    var elem = snippet.cloneNode(true);
    elems.appendChild(elem);
    elem.setAttribute('id', namespace + '-' + id);
    elem.setAttribute('data-id', id);
    addEditableTextElementEvents(namespace, elem)
    elem.querySelector('.' + namespace + '-name').textContent = name;
    elem.querySelector('.' + namespace + '-name-edit').value = name;
}

function addEditableTextElementEvents(namespace, elem)
{
    var elemId = elem.getAttribute('data-id');

    elem.querySelector('.' + namespace + '-delete').addEventListener('click', function() {
        axios.delete('/' + namespace + 's/' + elemId)
        .then(function (response) {
            elem.parentNode.removeChild(elem);
            console.log(response);
        })
        .catch(function (error) {
            console.log(error.response);
        });
    }, false);

    var elemUpdateElement = elem.querySelector('.' + namespace + '-update');
    elemUpdateElement.addEventListener('click', function() {
        var elemNameElement = elem.querySelector('.' + namespace + '-name');
        var elemNameEditElement = elem.querySelector('.' + namespace + '-name-edit');
        function updateElemName() {
            axios.put('/' + namespace + 's/' + elemId, {
                'name': elemNameEditElement.value
            })
            .then(function (response) {
                elemNameElement.textContent = elemNameEditElement.value;
                showHide(elemNameElement, elemNameEditElement);
                elemNameEditElement.classList.remove("error");
                console.log(response);
            })
            .catch(function (error) {
                elemNameEditElement.classList.add("error");
                console.log(error.response);
            });
        }
        showHide(elemNameElement, elemNameEditElement);        
        elemNameEditElement.addEventListener("blur", updateElemName);
        elemNameEditElement.focus();
    }, false);
}