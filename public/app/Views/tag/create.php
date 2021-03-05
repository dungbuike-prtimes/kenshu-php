<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post</title>
    <link rel="stylesheet" href="../../../css/styles.css">
</head>
<body>
<header class="header">
    <div class="header__wrapper">
        <div class="header__logo">Logo</div>
        <div class="header__nav">
            <a class="header__nav-item">Home</a>
            <a class="header__nav-item">Logout</a>
        </div>
    </div>
</header>
<div class="main">
    <div class="main__wrapper">
        <div class="content-container">
            <div class="content-container__header">
                <h2>Create Tag</h2>
            </div>
            <div class="content-container__body">
                <?php
                if (isset($message['type'])) {
                    switch ($message['type']) {
                        case 'error': {
                            echo "<div class=\"form__message form__message--error\">" . $message['message'] . "</div>";
                            break;
                        }
                        case 'success': {
                            echo "<div class=\"form__message form__message--success\">" . $message['message'] . "</div>";
                            break;
                        }
                        case 'warning': {
                            echo "<div class=\"form__message form__message--warning\">" . $message['message'] . "</div>";
                            break;
                        }
                    }
                }
                ?>

                <form class="form--create-tag" method="post" action="/tag/create">
                    <input name="name" type="text" placeholder="Tag name" class="form__input">
                    <textarea name="description" type="text" placeholder="Tag description" class="form__text-area"></textarea>
                    <div class="form__button-group">
                        <input type="submit" value="Create" class="form__button--success">
                        <input type="button" value="Cancel" class="form__button--cancel">
                    </div>
                </form>
            </div>
        </div>
        <div class="side-container">
            <div class="side-container__header">
                <h2>Side Content</h2>
            </div>
            <div class="side-container__body">
                <h3 class="username">Hello Nekko</h3>
                <p class="email">nekko@prtimes.co.jp</p>
            </div>
        </div>
    </div>
</div>
</body>
</html>