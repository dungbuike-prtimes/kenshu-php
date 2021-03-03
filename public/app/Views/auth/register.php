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
        <form class="form__body" method="post" action="/auth/postRegister">
            <?php if (isset($error)) {
                echo             '<div class="form__message">' . $error . '</div>';
            }
            ?>
            <div class="form__field">
                <label class="form__field-label" for="email">Email</label>
                <input class="form__field-input" placeholder="Email" type="text" name="email"/>
            </div>
            <div class="form__field">
                <label class="form__field-label" for="username">Username</label>
                <input class="form__field-input" placeholder="Username" type="text" name="username"/>
            </div>
            <div class="form__field">
                <label class="form__field-label" for="phone_number">Phone Number</label>
                <input class="form__field-input" placeholder="Phone Number" type="text" name="phone_number"/>
            </div>
            <div class="form__field">
                <label class="form__field-label" for="password">Password</label>
                <input class="form__field-input" placeholder="Password" type="password" name="password"/>
            </div>
            <div class="form__field">
                <label class="form__field-label" for="confirm_password">Confirm Password</label>
                <input class="form__field-input" placeholder="Confirm Password" type="password" name="confirm_password"/>
            </div>
            <input type="submit" class="form__submit" value="Submit" />
        </form>
    </div>
</div>
</body>
</html>

