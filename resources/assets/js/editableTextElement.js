export {
    addEditableTextElement,
    addEditableTextElementEvents,
    registerEditableTextElementAddBtn
};

import {
    addDateTimePicker
} from './dateTimePicker';

import {
    triggerEvent
} from './utils';

function funcOnEnter(elem, func) {
    elem.addEventListener('keydown', event => {
        if (event.keyCode == 13) {
            func();
        }
    });
}

function blurOnEnter(elem) {
    funcOnEnter(elem, () => triggerEvent(elem, 'blur'));
}

function show(elem)
{
    elem.style.display = 'block';
}

function hide(elem)
{
    elem.style.display = 'none';
}

function moveUp(elem)
{
    if (elem.previousSibling) {
        elem.parentNode.insertBefore(elem, elem.previousSibling);
    }
}

function moveDown(elem)
{
    if (elem.nextSibling && elem.nextSibling.nextSibling) {
        elem.parentNode.insertBefore(elem, elem.nextSibling.nextSibling);
    }
}

function registerEditableTextElementAddBtn(elems, snippet, namespace, btn, defaultsCallback,
    gettersCallback, settersCallback = function() {}, eventsCallback = function() {},
    parent = document
) {
    var newElemName = parent.querySelector('.new-' + namespace + '-name');
    
    function createElement() {
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

    btn.addEventListener('click', createElement);
    if (newElemName != null) {
        funcOnEnter(newElemName, createElement);
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

    var elemMoveUp = elem.querySelector('.' + namespace + '-up');
    var elemMoveDown = elem.querySelector('.' + namespace + '-down');

    if (elemMoveUp) {
        elemMoveUp.addEventListener('click', function() {
            axios.put('/' + namespace + 's/' + elemId + '/priority', {
                priority: 1
            })
            .then(function (response) {
                moveUp(elem);
                console.log(response);
            })
            .catch(function (error) {
                console.log(error);
            });
        }, false);
    }

    if (elemMoveDown) {
        elemMoveDown.addEventListener('click', function() {
            axios.put('/' + namespace + 's/' + elemId + '/priority', {
                priority: -1
            })
            .then(function (response) {
                moveDown(elem);
                console.log(response);
            })
            .catch(function (error) {
                console.log(error);
            });
        }, false);
    }

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
        blurOnEnter(elemDeadlineField);
    }

    elemNameEditElement.addEventListener('blur', updateElem);
    blurOnEnter(elemNameEditElement);
    
    elemUpdateElement.addEventListener('click', function() {
        hide(elemNameElement);
        show(elemNameEditElement);
        elemNameEditElement.focus();
    }, false);

    eventsCallback(elem, updateElem);
}