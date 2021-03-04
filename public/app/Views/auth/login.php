<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../../css/auth.css">
</head>
<body>
<div class='container'>
    <div class='form'>
        <div class='form__header'>
            <h1 class='form__header-text'>Login</h1>
        </div>
        <form class="form__body" method="post" action="/auth/login">
            <?php if (isset($error)) {
                echo             '<div class="form__message">' . $error . '</div>';
            }
            ?>
            <div class="form__field">
                <label class="form__field-label" for="email">Email</label>
                <input class="form__field-input" placeholder="Email" type="text" name="email"/>
            </div>
            <div class="form__field">
                <label class="form__field-label" for="password">Password</label>
                <input class="form__field-input" placeholder="Password" type="password" name="password"/>
            </div>
            <input type="submit" class="form__submit" value="Submit" />
        </form>
    </div>
</div>
</body>
</html>

