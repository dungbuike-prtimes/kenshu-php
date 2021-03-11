<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post</title>
    <link rel="stylesheet" href="../../../css/styles.css">
    <link rel="stylesheet" href="../../../css/post.css">
</head>
<body>
<?php
include_once __DIR__."/../layouts/header.php";
?>
<div class="main">
    <div class="main__wrapper">
        <div class="content-container">
            <div class="content-container__header">
                <h2><?php echo $data['post']['title']; ?></h2>

            </div>
            <div class="content-container__body">

                <div id="form" class="form">
                    <?php
                    include_once __DIR__."/../components/message.php";
                    ?>

                    <div class="form__field">
                        <p><?php echo $data['post']['content'];
                            ?></p>
                        <div class="form__image-preview">
                            <?php
                            foreach ($data['post']['images'] as $image) {
                                echo '<div class="form__image-preview-box">';
                                echo '<img src="' . $image['url'] . '">';
                                echo '</div>';
                            }
                            ?>
                        </div>
                        <div class="form__tag-field" id="tagField">
                            <?php
                            foreach ($data['post']['tags'] as $tag)
                            {
                                echo '<div class="form__tag-group">';
                                echo '<input name="tags[]" type="hidden" value="' . $tag['id'] . '">';
                                echo '<span class="form__tag">' . $tag['name'] . '</span></div>';
                            }
                            ?>
                        </div>

                    </div>
                    <div class="form__button-group">
                        <input id="cancel-button" type="button" class="form__button--cancel" value="Back">
                        <a type="button" href="/posts/<?php echo($data['post']['id']); ?>/edit"
                           class="form__button--success --pull-right">Edit Post</a>
                        <input id="delete-button" type="button" class="form__button--danger --pull-right" value="Delete this post">
                    </div>
                </div>
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
<div id="myModal" class="modal">

    <!-- Modal content -->
    <div class="modal__header">
        <span class="close">&times;</span>
        <h3>Are you sure to delete this post</h3>
    </div>
    <div class="modal__content">
        <form method="post" action="/posts/<?php echo($data['post']['id']); ?>">
            <input type="hidden" name="__method" value="DELETE">
            <input type="submit" class="form__button--danger" value="Sure, delete post!">
            <input type="button" class="form__button--success" value="No, keep post!">
        </form>
    </div>

</div>

</body>
<script language="JavaScript">
    let form = document.getElementById('form');
    let tagSelect = document.getElementById('tagSelect');
    let tagField = document.getElementById('tagField');
    let tagSelectedArr = [];
    <?php
    foreach ($data['post']['tags'] as $tag) {
        echo 'tagSelectedArr.push("' . $tag['id'] . '");';
    }
    ?>
    let insertedTags = document.querySelectorAll('.form__tag-group');
    insertedTags.forEach( element => {
        element.addEventListener('click', (event) => {
            element.remove();
            for( let i = 0; i < tagSelectedArr.length; i++){
                if ( tagSelectedArr[i] === element.childNodes[0].value) {
                    tagSelectedArr.splice(i, 1);
                }
            }
        })
    })

    let cancelButton = document.getElementById('cancel-button');
    cancelButton.addEventListener('click', () => {
        history.back();
    })
    let modal = document.getElementById("myModal");

    // Get the button that opens the modal
    let btn = document.getElementById("delete-button");

    // Get the <span> element that closes the modal
    let span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

</script>
</html>