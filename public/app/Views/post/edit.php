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
                <h2>Content</h2>
            </div>
            <div class="content-container__body">

                <form id="form" class="form"
                      action="/posts/<?php echo $data['post']['id']; ?>" method="post"
                      enctype="multipart/form-data">
                    <input type="hidden" name="__method" value="PUT">
                    <input type="hidden" name="csrf_token" value="<?php echo $data['csrf_token'];?>">

                    <?php
                    include_once __DIR__."/../components/message.php";
                    ?>

                    <div class="form__field">
                        <label class="form__label" for="title">Title</label>
                        <input type="text" class="form__input" name="title" placeholder="Post Title"
                               value="<?php echo h($data['post']['title']); ?>">
                    </div>
                    <div class="form__field">
                        <label class="form__label" for="content">Content</label>
                        <textarea class="form__text-area" name="content" placeholder="Post content"
                        ><?php echo h($data['post']['content']);
                        ?></textarea>
                    </div>
                    <div class="form__field">
                        <label class="form__label" for="tag">Tag</label>
                        <div class="form__tag-field" id="tagField">
                            <?php
                                foreach ($data['post']['tags'] as $tag)
                                {
                                    echo '<div class="form__tag-group">';
                                    echo '<input name="tags[]" type="hidden" value="' . $tag['id'] . '">';
                                    echo '<span class="form__tag">' . h($tag['name']) . '</span></div>';
                                }
                            ?>
                            <input id="create-tag" type="button" value="+ Create Tag" class="form__button--success --pull-right">
                        </div>
                        <select id="tagSelect" class="form__input form__input--select2">
                            <option value="" selected disabled>Choose Tag</option>
                            <?php
                            foreach ($data['tags'] as $tag) {
                                echo '<option value="'.$tag["id"].'">'. h($tag["NAME"]).'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form__field">
                        <label class="form__label" for="file-upload">Image</label>
                        <input id="file-upload" type="file" class="form__file-upload" multiple name="images[]"
                               placeholder="Upload Image">
                        <div class="form__image-preview">
                            <?php
                            foreach ($data['post']['images'] as $image) {
                                echo '<div class="form__image-preview-box">';
                                echo '<img src="' . h($image['url']) . '">';
                                echo '<a type="button" class="form__button--danger --bottom --center delete-image">Delete</a>';
                                echo '<input type="hidden" value="' . $image['id'] . '"></div>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="form__button-group">
                        <input type="submit" class="form__button--success" value="Update Post">
                        <input id="cancel-button" type="button" class="form__button--cancel" value="Cancel">
                        <input id="delete-button" type="button" class="form__button--danger --pull-right" value="Delete this post">
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
<div id="myModal" class="modal">

    <!-- Modal content -->
    <div class="modal__header">
        <span class="close">&times;</span>
        <h3>Are you sure to delete this post</h3>
    </div>
    <div class="modal__content">
        <form method="post" action="/posts/<?php echo($data['post']['id']); ?>">
            <input type="hidden" name="csrf_token" value="<?php echo $data['csrf_token'];?>">
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

    tagSelect.addEventListener('change', () => {
        let val = tagSelect.value;
        if (tagSelectedArr.indexOf(val) === -1) {
            let tagGroup = document.createElement('div');
            tagGroup.classList.add('form__tag-group');

            let tagValue = document.createElement('input');
            tagValue.hidden = true;
            tagValue.value = val;
            tagValue.name = 'tags[]';

            let tag = document.createElement('span');
            tag.classList.add('form__tag');
            let option_user_selection = tagSelect.options[val].textContent;
            tag.textContent = option_user_selection;
            tag.addEventListener('click', (event) => {
                tag.remove();
                for( let i = 0; i < tagSelectedArr.length; i++){
                    if ( tagSelectedArr[i] === tagValue.value) {
                        tagSelectedArr.splice(i, 1);
                    }
                }
            })

            tagGroup.appendChild(tagValue);
            tagGroup.appendChild(tag);
            tagField.prepend(tagGroup);

            tagSelectedArr.push(val);
        }
    })

    let createTag = document.getElementById('create-tag');
    createTag.addEventListener('click', () => {
        window.location.href = '/tags/create';
    })

    let deleteImage = document.querySelectorAll('.delete-image');
    deleteImage.forEach( element => {
        element.addEventListener('click', () => {
            let deleteImageHiddenInput = document.createElement('input');
            deleteImageHiddenInput.hidden = true;
            deleteImageHiddenInput.name = "deleteImage[]";
            deleteImageHiddenInput.value = element.nextSibling.value;
            form.appendChild(deleteImageHiddenInput);
            element.parentNode.remove();
        })
    })

    let cancelButton = document.getElementById('cancel-button');
    cancelButton.addEventListener('click', () => {
        window.location.href = '/posts'
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