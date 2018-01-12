console.log('>', 'show-details.js loaded :)');

const detailsElement = document.querySelector('#details');

const employeesElements = document.querySelectorAll('.employee-item');
for (let i = 0, k = employeesElements.length; i < k; i++) {

    const employeeId = employeesElements[i].attributes['employeeId'].textContent;
    employeesElements[i].addEventListener('click', function() {

        getDetailsAsync(employeeId);
    });
}

const getDetailsAsync = function (employeeId) {

    fetch('app_service.php', {
        method: 'post',
        headers: {
            'Content-Type':'application/x-www-form-urlencoded'
        },
        body: 'employee-id=' + employeeId
    }).then(function (response) {
        return response.json();
    }).then(function (result) {
        console.log(employeeId, result);
        if (result.success) {

            detailsElement.innerHTML = (
                '<h2>Szczegóły o pracowniku</h2>' +
                '<a class="button" href="index.php?a=edit-employee&id=' + result.employee.id + '">Edytuj pracownika</a>' +
                '<a class="button" href="index.php?a=del-employee&id=' + result.employee.id + '">Zwolnij pracownika</a>' +
                '<dl>' +
                '<dt>Imię</dt><dd>' + result.employee.forename +'</dd>' +
                '<dt>Nazwisko</dt><dd>' + result.employee.surname + '</dd>' +
                '<dt>Id (tmp)</dt><dd>' + result.employee.id + '</dd>' +
                '<dt>...</dt><dd>...</dd>' +
                '<dl>'
            );
            
        } else {
            console.log('Nie ma pracownika o id ' + employeeId);
        }
    });
}
