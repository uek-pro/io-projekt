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
</div>

<form method="post" action="">

    <h2>Zaloguj się</h2>

    <label>
        Login:
        <input name="login" type="text" />
    </label>

    <label>
        Hasło:
        <input name="password" type="password" />
    </label>
    
    <input type="submit" value="Zaloguj" />

</form>

<form method="post" action="">

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
    
    <input type="submit" value="Zarejestruj" />

</form>