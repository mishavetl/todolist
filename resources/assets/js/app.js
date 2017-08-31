require('./bootstrap');

axios.interceptors.response.use(undefined, error => {
    const response = error.response;
    if (response.status === 401) {
        window.location.reload();
    }
    return Promise.reject(error);
});

import {addEditableTextElement, addEditableTextElementEvents} from './editableTextElement';

var todolistAddBtn = document.querySelector('#add-todolist');
var taskAddBtn = document.querySelector('#add-task');
var todolistSnippet = document.querySelector('#todolist-snippet').lastChild;
var taskSnippet = document.querySelector('#task-snippet');
var todolists = document.querySelector('#todolists');

todolistAddBtn.addEventListener("click", function() {
    axios.post('/todolists', {
        'name': "Todo list"
    })
    .then(function (response) {
        addEditableTextElement(todolists, todolistSnippet, 'todolist', response.data.id, response.data.name);
        console.log(response);
    })
    .catch(function (error) {
        console.log(error.response);
    });
});

// registerEditableTextElementAddBtn(todolists, todolistSnippet, 'todolist', todolistAddBtn);

var todolistObjects = document.querySelectorAll('.todolist');

for (var i = 0; i < todolistObjects.length; i++){
    addEditableTextElementEvents('todolist', todolistObjects[i]);
}