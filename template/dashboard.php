<div class="header">
    <div class="logo">
        <a href="">
            <object class="logo" type="image/svg+xml" data="template/images/logo.svg">
                Logo
                <!-- fallback image in CSS -->
            </object>
        </a>
    </div>
    <h1>IOProject - Menadżer pracowników</h1>
    <p>zalogowany jako: <span><?= $_SESSION['logged_user'] ?></span> | <a href="index.php?a=logout">wyloguj</a>
</div>

<div class="main">

    <h2>Lista pracowników <span>(Aktualnie zatrudnionych 4)</span></h2>

    <input type="button" value="Dodaj nowego pracownika" />

    <div class="employees-list">
        <div class="employee-item">
            <div class="rectangle green"><div>
            <p>Daniel Orzeł</p>
            <p>6600 zł</p>
        </div>

        <div class="employee-item">
            <div class="rectangle blue"><div>
            <p>Edward Jaszczomb</p>
            <p>900 zł</p>
        </div>

        <div class="employee-item">
            <div class="rectangle green"><div>
            <p>Marta Kot</p>
            <p>5800 zł</p>
        </div>

        <div class="employee-item">
            <div class="rectangle purple"><div>
            <p>Joanna Gęś</p>
            <p>2277 zł</p>
        </div>
    </div>

</div>

<div class="details">

    <h2>Szczegóły o pracowniku</h2>

    <input type="button" value="Edytuj pracownika" />
    <input type="button" value="Zwolnij pracownika" />

    <dl>
        <dt>Wynagrodzenie netto</dt><dd>5020 zł</dd>
        <dt>Wynagrodzenie brutto</dt><dd>5800 zł</dd>
        <dt>Koszt pracownika</dt><dd>6600 zł</dd>
        <dt>...</dt><dd>...</dd>
    <dl>

</div>