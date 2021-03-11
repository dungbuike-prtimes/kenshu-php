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

                <form id="form" class="form" method="post" action="/posts" enctype="multipart/form-data">
                    <?php
                    include_once __DIR__."/../components/message.php";
                    ?>

                    <div class="form__field">
                        <label class="form__label" for="title">Title</label>
                        <input type="text" class="form__input" name="title" placeholder="Post Title">
                    </div>
                    <div class="form__field">
                        <label class="form__label" for="content">Content</label>
                        <textarea class="form__text-area" name="content" placeholder="Post content"></textarea>
                    </div>
                    <div class="form__field">
                        <label class="form__label" for="tag">Tag</label>
                        <div class="form__tag-field" id="tagField">
                            <input id="create-tag" type="button" value="+ Create Tag" class="form__button--success --pull-right">
                        </div>
                        <select id="tagSelect" class="form__input form__input--select2">
                            <option value="" selected disabled>Choose Tag</option>
                            <?php
                            foreach ($data['tags'] as $tag) {
                                echo '<option value="'.$tag["id"].'">'.$tag["NAME"].'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form__field">
                        <label class="form__label" for="file-upload">Image</label>
                        <input id="file-upload" type="file" class="form__file-upload" multiple name="images[]"
                               placeholder="Upload Image">
                    </div>
                    <div class="form__button-group">
                        <input type="submit" class="form__button--success" value="Create">
                        <input id="cancel-button" type="button" class="form__button--cancel" value="Cancel">
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
<script language="JavaScript">
    let tagSelect = document.getElementById('tagSelect');
    let tagField = document.getElementById('tagField');
    let tagSelectedArr = [];
    tagSelect.addEventListener('change', () => {
        let val = tagSelect.value;
        if (tagSelectedArr.indexOf(val) === -1) {
            let tagGroup = document.createElement('div');

            let tagValue = document.createElement('input');
            tagValue.hidden = true;
            tagValue.value = val;
            tagValue.name = 'tag[]';

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

    let cancelButton = document.getElementById('cancel-button');
    cancelButton.addEventListener('click', () => {
        window.location.href = '/posts'
    })

</script>
</html>