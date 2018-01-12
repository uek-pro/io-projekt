<?php use IOProject\Core\Config; ?>

<div class="header">
    <div class="logo">
        <a href="">
            <object class="logo" type="image/svg+xml" data="template/images/logo.svg">
                Logo
                <!-- fallback image in CSS -->
            </object>
        </a>
    </div>
    <h1><?= Config::ApplicationTitle ?></h1>
</div>

<form method="post" action="index.php?a=logon">

    <h2>Zaloguj się</h2>

    <label>
        Login:
        <input name="login" type="text" />
    </label>

    <label>
        Hasło:
        <input name="password" type="password" />
    </label>

    <?php if (isset($_SESSION['logon_bad_attempt'])) {
        echo '<p>Błędny login lub hasło</p>';
        unset($_SESSION['logon_bad_attempt']);
    } ?>
    
    <input type="submit" value="Zaloguj" />

</form>

<form method="post" action="index.php?a=register">

    <h2>Nowe konto</h2>

    <label>
        Login:
        <input name="login-register" type="text" />
    </label>
    
    <label>
        Hasło:
        <input name="password-register" type="password" />
    </label>
    
    <label>
        Powtórz hasło:
        <input name="password-repeat-register" type="password" />
    </label>

    <?php if (isset($_SESSION['register_attempt'])) {
        echo '<p>' . $_SESSION['register_attempt'] . '</p>';
        unset($_SESSION['register_attempt']);
    } ?>
    
    <input type="submit" value="Zarejestruj" />

</form>