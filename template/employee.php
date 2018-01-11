<h1>IOProject - Menadżer pracowników</h1>

<form method="post" action="index.php?a=add-employee">

    <h2>Dodaj / Edytuj pracownika</h2>

    <label>
        Imię:
        <input name="forename" type="text" />
    </label>
    
    <label>
        Nazwisko:
        <input name="surname" type="text" />
    </label>

    <label>
        PESEL:
        <input name="pesel" type="number" />
    </label>

    <label>
        Numer konta:
        <input name="account-number" type="number" />
    </label>
    
    <label>
        Rodzaj umowy:
        <select name="contract-type">
            <option value="0">Umowa o pracę</option>
            <option value="1">Umowa zlecenie</option>
            <option value="2">Umowa o dzieło</option>
        </select>
    </label>

    <label>
        Wynagrodzenie netto:
        <input name="net-salary" type="number" />
    </label>

    <label>
        Wynagrodzenie brutto:
        <input name="gross-salary" type="number" />
    </label>

    <label>
        Koszt pracownika:
        <input name="cost-of-employer" type="number" />
    </label>

    <input type="submit" value="Dodaj / Edytuj" />
    <a href="index.php">Powrót</a>

</form>