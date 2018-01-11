<?php namespace IOProject;

use IOProject\Core\Common;
use IOProject\Accountancy\Enums\ContractType;

class View {

    public static function generateEmployeesList($employees) {
        
        if (!$employees || count($employees) == 0) return null;

        $content = '';

        for ($i = 0; $i < count($employees); $i++) {

            $contractTypeColor = 'black';
            
            $contractType = $employees[$i]->getContractType();
            if ($contractType == ContractType::EmploymentContract) {
                $contractTypeColor = 'green';
            } else if ($contractType == ContractType::MandatoryContract) {
                $contractTypeColor = 'blue';
            } else if ($contractType == ContractType::SpecificTaskContract) {
                $contractTypeColor = 'purple';
            }

            $content .= '<div class="employee-item">' . '<div class="rectangle ' . $contractTypeColor . '"></div><p>' . $employees[$i]->getForename() . ' ' . $employees[$i]->getSurname() . '</p><p>' . $employees[$i]->getCostOfEmployer() . '</p>' . '</div>';
        }

        return ($content != '' ? '<div class="employees-list">' . $content . '</div>' : null);
    }

    public static function generateTotalEmployeesCost($employees) {

        if (!$employees || count($employees) == 0) return null;

        $cost = 0;
        for ($i = 0, $c = count($employees); $i < $c; $i++) {
            $cost += $employees[$i]->getCostOfEmployer();
        }

        return '<p>Całkowity koszt pracowników:</p><p>' . Common::changeCost($cost) . '</p>';
    }

    public static function generateCurrentEmployeesCount($employees) {

        if (!$employees || count($employees) == 0) return null;

        return (count($employees) != 0 ? ' <span>(Aktualnie zatrudnionych ' . count($employees) . ')</span>' : null);
    }
}