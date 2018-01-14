console.log('*', 'show-details.js loaded :)');

const detailsElement = document.querySelector('#details');

const employeesElements = document.querySelectorAll('.employee-item');
for (let i = 0, k = employeesElements.length; i < k; i++) {

    const employeeId = employeesElements[i].attributes['employeeId'].textContent;
    employeesElements[i].addEventListener('click', function() {
        
        const previousSelectedEmployee = detailsElement.getAttribute('current-item');
        if (i != previousSelectedEmployee) {
            previousSelectedEmployee != null ? selection.unselect(employeesElements[previousSelectedEmployee]) : null;

            detailsElement.setAttribute('current-item', i);
            selection.select(employeesElements[i]);

            detailsElement.innerHTML = (
                '<h2>Szczegóły</h2>' +
                '<a class="button" href="index.php?a=edit-employee&id=' + employeeId + '">Edytuj pracownika</a>' +
                '<a class="button red" href="index.php?a=del-employee&id=' + employeeId + '">Zwolnij pracownika</a>'
            );

            getDetailsAsync(employeeId);
        }
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

            let contractTypeContent = 'N/A';
            const contractType = result.employee.contractType;
            if (contractType == 0) {
                contractTypeContent = 'Umowa o pracę';
            } else if (contractType == 1) {
                contractTypeContent = 'Umowa zlecenie';
            } else if (contractType == 2) {
                contractTypeContent = 'Umowa o dzieło';
            }

            detailsElement.innerHTML += (
                '<dl>' +
                '<dt>Imię</dt><dd>' + result.employee.forename +'</dd>' +
                '<dt>Nazwisko</dt><dd>' + result.employee.surname + '</dd>' +
                '<dt>PESEL</dt><dd>' + result.employee.PESEL + '</dd>' +
                '<dt>Nr konta</dt><dd>' + result.employee.accountNumber + '</dd>' +
                '<dt>Typ umowy</dt><dd>' + contractTypeContent + '</dd>' +
                '</dl>' +
                '<p>Całkowity koszt pracownika</p>' +
                '<p class="cost">' + changeCost(result.calculation.costOfEmployer) + '</p>' +
                '<dl>' +
                '<dt>' + result.calculation.taxes['pension-insurance'].name + '</dt><dd>' + changeCost(result.calculation.taxes['pension-insurance'].outcome) +'</dd>' +
                '<dt>' + result.calculation.taxes['disability-insurance'].name + '</dt><dd>' + changeCost(result.calculation.taxes['disability-insurance'].outcome) +'</dd>' +
                '<dt>' + result.calculation.taxes['accident-insurance'].name + '</dt><dd>' + changeCost(result.calculation.taxes['accident-insurance'].outcome) +'</dd>' +
                '<dt>' + result.calculation.taxes['labor-fund'].name + '</dt><dd>' + changeCost(result.calculation.taxes['labor-fund'].outcome) +'</dd>' +
                '<dt>' + result.calculation.taxes['gebf'].name + '</dt><dd>' + changeCost(result.calculation.taxes['gebf'].outcome) +'</dd>' +
                '</dl>' +
                '<p>Wynagrodzenie brutto</p>' +
                '<p class="cost">' + changeCost(result.calculation.grossSalary) + '</p>' +
                '<dl>' +
                '<dt>' + result.calculation.taxes['pension-contribution'].name + '</dt><dd>' + changeCost(result.calculation.taxes['pension-contribution'].outcome) +'</dd>' +
                '<dt>' + result.calculation.taxes['disability-contribution'].name + '</dt><dd>' + changeCost(result.calculation.taxes['disability-contribution'].outcome) +'</dd>' +
                '<dt>' + result.calculation.taxes['sickness-contribution'].name + '</dt><dd>' + changeCost(result.calculation.taxes['sickness-contribution'].outcome) +'</dd>' +
                '<dt>' + result.calculation.taxes['health-insurance'].name + '</dt><dd>' + changeCost(result.calculation.taxes['health-insurance'].outcome) +'</dd>' +
                '<dt>' + result.calculation.taxes['advance'].name + '</dt><dd>' + changeCost(result.calculation.taxes['advance'].outcome, 0) + '</dd>' +
                '</dl>' +
                '<p>Wynagrodzenie netto</p>' +
                '<p class="cost">' + changeCost(result.calculation.netSalary) + '</p>' 
            );
            
        } else {
            console.log('Nie ma pracownika o id ' + employeeId);
        }
    });
}

const selection = {
    select: function(handle) {
        handle.className == 'employee-item' ? handle.className += " selected" : null;
    },
    unselect: function(handle) {
        handle.className == 'employee-item selected' ? handle.className = "employee-item" : null;
    }
}

function changeCost(amount, precision = 2) {
    return Number(Math.round(amount * Math.pow(10, precision)) / Math.pow(10, precision)).toFixed(2) + ' ' + consts.currency;
}