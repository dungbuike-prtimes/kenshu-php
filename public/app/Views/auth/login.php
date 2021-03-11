<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../../css/auth.css">
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
<div class='container'>
    <div class='form'>
        <div class='form__header'>
            <h1 class='form__header-text'>Login</h1>
            <span>Don't have an account? <a href="/auth/register">Register Now</a></span>
            <span>or back to <a href="/">Homepage</a></span>
        </div>
        <form class="form__body" method="post" action="/auth">
            <?php
            include_once __DIR__."/../components/message.php";
            ?>
            <div class="form__field">
                <label class="form__field-label" for="email">Email</label>
                <input class="form__field-input" placeholder="Email" type="text" name="email"/>
            </div>
            <div class="form__field">
                <label class="form__field-label" for="password">Password</label>
                <input class="form__field-input" placeholder="Password" type="password" name="password"/>
            </div>
            <input type="submit" class="form__submit form__button--success" value="Login" />
        </form>
    </div>
</div>
</body>
</html>

