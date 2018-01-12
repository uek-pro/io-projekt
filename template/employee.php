<?php 

use IOProject\Core\Config;
use IOProject\Accountancy\Enums\ContractType;

$change = isset($_SESSION['employee_changing']) ? true : false;

?>

<div class="container employee-page">

    <h1><?= Config::ApplicationTitle ?></h1>
    <h2><?= $change ? 'Edytuj' : 'Zatrudnij' ?> pracownika</h2>

    <form method="post" action="index.php?a=add-employee">

        <input name="id" type="hidden" value="<?= $change ? $_SESSION['employee_changing']->getId() : null ?>"/>

        <label>
            Imię:
            <input name="forename" type="text" value="<?= $change ? $_SESSION['employee_changing']->getForename() : null ?>" />
        </label>
        
        <label>
            Nazwisko:
            <input name="surname" type="text" value="<?= $change ? $_SESSION['employee_changing']->getSurname() : null ?>"/>
        </label>

        <label>
            PESEL:
            <input name="pesel" type="number" value="<?= $change ? $_SESSION['employee_changing']->getPESEL() : null ?>"/>
        </label>

        <label>
            Numer konta:
            <input name="account-number" type="number" value="<?= $change ? $_SESSION['employee_changing']->getAccountNumber() : null ?>"/>
        </label>
        
        <label>
            Rodzaj umowy:
            <select name="contract-type">
                <option value="0"<?= $change && $_SESSION['employee_changing']->getContractType() == ContractType::EmploymentContract ? ' selected="selected"' : null ?>>Umowa o pracę</option>
                <option value="1"<?= $change && $_SESSION['employee_changing']->getContractType() == ContractType::MandatoryContract ? ' selected="selected"' : null ?>>Umowa zlecenie</option>
                <option value="2"<?= $change && $_SESSION['employee_changing']->getContractType() == ContractType::SpecificTaskContract ? ' selected="selected"' : null ?>>Umowa o dzieło</option>
            </select>
        </label>

        <label>
            Wynagrodzenie netto:
            <input name="net-salary" type="number" step="0.01" value="<?= $change ? $_SESSION['employee_changing']->getNetSalary() : null ?>"/>
        </label>

        <label>
            Wynagrodzenie brutto:
            <input name="gross-salary" type="number" step="0.01" value="<?= $change ? $_SESSION['employee_changing']->getGrossSalary() : null ?>"/>
        </label>

        <label>
            Koszt pracownika:
            <input name="cost-of-employer" type="number" step="0.01" value="<?= $change ? $_SESSION['employee_changing']->getCostOfEmployer() : null ?>"/>
        </label>

        <input type="submit" value="<?= $change ? 'Zapisz zmiany' : 'Zatrudnij' ?>" />
        <a class="button" href="index.php">Powrót</a>

    </form>

</div>