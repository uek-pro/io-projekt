<?php use IOProject\Core\Config; ?>

<div class="container mainpage">

    <div class="header">
        <div class="logo">
            <a href="">
                <object class="logo" type="image/svg+xml" data="template/images/logo.svg">
                    Logo
                    <!-- fallback image in CSS -->
                </object>
            </a>
        </div>
        <h1><?= Config::APPLICATION_TITLE ?></h1>
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
            echo '<span class="info">Błędny login lub hasło</span>';
            unset($_SESSION['logon_bad_attempt']);
        } ?>
        
        <input type="submit" value="Zaloguj" />

    </form><!--

    --><form method="post" action="index.php?a=register">

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
            echo '<span class="info">' . $_SESSION['register_attempt'] . '</span>';
            unset($_SESSION['register_attempt']);
        } ?>
        
        <input type="submit" value="Zarejestruj" />

    </form>

</div>