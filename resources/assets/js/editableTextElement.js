export {
    addEditableTextElement,
    addEditableTextElementEvents,
    registerEditableTextElementAddBtn
};

import {
    addDateTimePicker
} from './dateTimePicker';

// function showHide(elem1, elem2)
// {
//     var elem1Display = elem1.style.display;
//     elem1.style.display = elem2.style.display;
//     elem2.style.display = elem1Display;
// }

function show(elem)
{
    elem.style.display = 'block';
}

function hide(elem)
{
    elem.style.display = 'none';
}

function registerEditableTextElementAddBtn(elems, snippet, namespace, btn, defaultsCallback,
    gettersCallback, settersCallback = function() {}, eventsCallback = function() {},
    parent = document
) {
    var newElemName = parent.querySelector('.new-' + namespace + '-name');
    
    function createElem() {
        axios.post('/' + namespace + 's', defaultsCallback(parent))
        .then(function (response) {
            if (newElemName != null) {
                newElemName.value = '';
                newElemName.classList.remove('error');
            }            
            addEditableTextElement(elems, snippet, namespace, response.data,
                gettersCallback, eventsCallback, settersCallback);
            console.log(response);
        })
        .catch(function (error) {
            if (newElemName != null) newElemName.classList.add('error');
            console.log(error);
        });
    }

    btn.addEventListener('click', createElem);
    if (newElemName != null) {
        newElemName.addEventListener('keydown', event => {
            if (event.keyCode == 13) {
                createElem();
            }
        });
    }
}

function addEditableTextElement(elems, snippet, namespace, data, gettersCallback, settersCallback, eventsCallback)
{
    var elem = snippet.cloneNode(true);
    elems.appendChild(elem);
    elem.setAttribute('id', namespace + '-' + data.id);
    elem.setAttribute('data-id', data.id);
    elem.querySelector('.' + namespace + '-name').textContent = data.name;
    elem.querySelector('.' + namespace + '-name-edit').value = data.name;
    addEditableTextElementEvents(namespace, elem, gettersCallback, eventsCallback);
    settersCallback(data, elem);
}

function addEditableTextElementEvents(namespace, elem, gettersCallback, eventsCallback = function () {})
{
    var elemId = elem.getAttribute('data-id');

    elem.querySelector('.' + namespace + '-delete').addEventListener('click', function() {
        axios.delete('/' + namespace + 's/' + elemId)
        .then(function (response) {
            elem.parentNode.removeChild(elem);
            console.log(response);
        })
        .catch(function (error) {
            console.log(error);
        });
    }, false);

    var elemDeadline = elem.querySelector('.' + namespace + '-deadline');
    var elemNameElement = elem.querySelector('.' + namespace + '-name');
    var elemNameEditElement = elem.querySelector('.' + namespace + '-name-edit');
    var elemUpdateElement = elem.querySelector('.' + namespace + '-update');

    function updateElem() {
        axios.put('/' + namespace + 's/' + elemId, gettersCallback(elem))
        .then(function (response) {
            elemNameElement.textContent = elemNameEditElement.value;
            show(elemNameElement);
            hide(elemNameEditElement);
            elemNameEditElement.classList.remove('error');
            console.log(response);
        })
        .catch(function (error) {
            hide(elemNameElement);
            show(elemNameEditElement);    
            elemNameEditElement.classList.add('error');
            console.log(error);
        });
    }

    if (elemDeadline != null) {
        var elemDeadlineField = elemDeadline.querySelector('.' + namespace + '-deadline-field');    
        addDateTimePicker($(elemDeadline));
        elemDeadlineField.addEventListener('blur', updateElem);
    }

    elemNameEditElement.addEventListener('blur', updateElem);
    elemNameEditElement.addEventListener('keydown', event => {
        if (event.keyCode == 13) {
            updateElem();
        }
    });
    
    elemUpdateElement.addEventListener('click', function() {
        hide(elemNameElement);
        show(elemNameEditElement);
        elemNameEditElement.focus();
    }, false);

    eventsCallback(elem, updateElem);
}