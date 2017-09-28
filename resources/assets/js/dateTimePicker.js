export {
    addDateTimePicker
};

function addDateTimePicker(elem) {
    elem.datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss'
    });
}