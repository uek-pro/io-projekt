<?php namespace IOProject;

use IOProject\Core\Common;
use IOProject\Accountancy\Enums\ContractType;

class View {

    public static function generateEmployeesList($employees) {
        
        if (count($employees) == 0) return null;

        $headers = '<div class="employee-header"><p>Imię i nazwisko <span class="contract-type">Typ umowy</span></p><p>Wynagrodzenie brutto (Koszt pracownika)</p></div>';

        $content = '';

        for ($i = 0; $i < count($employees); $i++) {

            $contractTypeShortcut = 'NA';
            $contractType = $employees[$i]->getContractType();
            if ($contractType == ContractType::EmploymentContract) {
                $contractTypeShortcut = 'UP';
            } else if ($contractType == ContractType::MandatoryContract) {
                $contractTypeShortcut = 'UZ';
            } else if ($contractType == ContractType::SpecificTaskContract) {
                $contractTypeShortcut = 'UD';
            }

            $content .= '<div class="employee-item" employeeId=' . $employees[$i]->getId() . '><p>' . $employees[$i]->getForename() . ' ' . $employees[$i]->getSurname() . ' <span class="contract-type">' . $contractTypeShortcut . '</span></p><p>' . Common::changeCost($employees[$i]->getGrossSalary()) . ' (' . Common::changeCost($employees[$i]->getCostOfEmployer()) . ')</p>' . '</div>';
        }

        return ($content != '' ? '<div class="employees-list">' . $headers . $content . '</div>' : null);
    }

    public static function generateTotalEmployeesCost($employees) {

        if (count($employees) == 0) return null;

        $cost = 0;
        for ($i = 0, $c = count($employees); $i < $c; $i++) {
            $cost += $employees[$i]->getCostOfEmployer();
        }

        return '<div class="summary"><p>Całkowity koszt pracowników</p><p class="cost">' . Common::changeCost($cost) . '</p></div>';
    }

    public static function generateCurrentEmployeesCount($employees) {

        if (count($employees) == 0) return null;

        return (count($employees) != 0 ? ' <span>(Aktualnie zatrudnionych ' . count($employees) . ')</span>' : null);
    }
}