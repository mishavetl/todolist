require('./bootstrap');
require('./myBootstrap');

import {
    addEditableTextElement,
    addEditableTextElementEvents,
    registerEditableTextElementAddBtn
} from './editableTextElement';

var todolistSnippet = document.querySelector('#todolist-snippet').lastChild;
var taskSnippet = document.querySelector('#task-snippet').children[0];

function setTaskFields(data, task)
{
    task.querySelector('.task-status').value = data.status;
    task.setAttribute('data-todolist-id', data.project_id);
}

function getTaskFields(task)
{
    return {
        'id': task.getAttribute('data-id'),
        'name': task.querySelector('.task-name-edit').value,
        'status': task.querySelector('.task-status').checked,
        'project_id': task.getAttribute('data-todolist-id')
    };
}

function getTodolistFields(todolist)
{
    return {
        'id': todolist.getAttribute('data-id'),        
        'name': todolist.querySelector('.todolist-name-edit').value,
    };
}

function addTaskEvents(task, updateElem)
{
    task.querySelector('.task-status').addEventListener('change', updateElem);
}

function addTodolistEvents(todolist)
{
    var tasks = todolist.querySelector('.tasks');
    var taskAddBtn = todolist.querySelector('.add-task');
    registerEditableTextElementAddBtn(tasks, taskSnippet, 'task', taskAddBtn, function(todolist) {
        return {
            'name': todolist.querySelector('.new-task-name').value,
            'status': false,
            'project_id': todolist.getAttribute('data-id')
        };
    }, getTaskFields, addTaskEvents, setTaskFields, todolist);
}

var todolists = document.querySelector('#todolists');
var todolistAddBtn = document.querySelector('#add-todolist');
registerEditableTextElementAddBtn(todolists, todolistSnippet, 'todolist', todolistAddBtn, function(parent) {
    return {
        'name': "Todo list"
    };
}, getTodolistFields, addTodolistEvents);

var todolistObjects = todolists.querySelectorAll('.todolist');
for (var i = 0; i < todolistObjects.length; i++){
    var todolist = todolistObjects[i];
    var tasks = todolist.querySelector('.tasks');    
    var taskObjects = tasks.querySelectorAll('.task');    
    
    addEditableTextElementEvents('todolist', todolist, getTodolistFields, addTodolistEvents);    
    for (var j = 0; j < taskObjects.length; j++) {
        addEditableTextElementEvents('task', taskObjects[j], getTaskFields, addTaskEvents);
    }
}