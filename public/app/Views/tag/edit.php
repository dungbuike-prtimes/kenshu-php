<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post</title>
    <link rel="stylesheet" href="../../../css/styles.css">
</head>
<body>
<?php
include_once __DIR__."/../layouts/header.php";
?>
<div class="main">
    <div class="main__wrapper">
        <div class="content-container">
            <div class="content-container__header">
                <h2>Edit Tag</h2>
            </div>
            <div class="content-container__body">
                <?php
                include_once __DIR__."/../components/message.php";
                ?>

                <form class="form--create-tag" method="post" action="/tags/<?php echo($data['tag']['id']); ?>/edit">
                    <input name="name" type="text" placeholder="Tag name" class="form__input"
                           value="<?php echo($data['tag']['name']); ?>">
                    <textarea name="description" type="text" placeholder="Tag description" class="form__text-area"><?php
                        echo($data['tag']['description']);
                        ?></textarea>
                    <div class="form__button-group">
                        <input type="submit" value="Create" class="form__button--success">
                        <input id="cancel-button" type="button" value="Cancel" class="form__button--cancel">
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
<script>
    let cancelButton = document.getElementById('cancel-button');
    cancelButton.addEventListener('click', () => {
        history.back();
    })
</script>
</html>