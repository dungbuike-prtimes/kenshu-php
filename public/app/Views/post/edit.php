<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post</title>
    <link rel="stylesheet" href="../../../css/styles.css">
    <link rel="stylesheet" href="../../../css/post.css">
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
                <h2>Content</h2>
            </div>
            <div class="content-container__body">

                <form id="form" class="form"
                      action="/post/edit/<?php echo $data['post']['id']; ?>" method="post"
                      enctype="multipart/form-data">
                    <input type="hidden" name="__method" value="PUT">
                    <?php
                    include_once __DIR__."/../components/message.php";
                    ?>

                    <div class="form__field">
                        <label class="form__label" for="title">Title</label>
                        <input type="text" class="form__input" name="title" placeholder="Post Title"
                               value="<?php echo $data['post']['title']; ?>">
                    </div>
                    <div class="form__field">
                        <label class="form__label" for="content">Content</label>
                        <textarea class="form__text-area" name="content" placeholder="Post content"
                        ><?php echo $data['post']['content'];
                        ?></textarea>
                    </div>
                    <div class="form__field">
                        <label class="form__label" for="tag">Tag</label>
                        <div class="form__tag-field" id="tagField">
                            <?php
                                foreach ($data['post']['tags'] as $tag)
                                {
                                    echo '<div class="form__tag-group">';
                                    echo '<input name="tag[]" type="hidden" value="' . $tag['id'] . '">';
                                    echo '<span class="form__tag">' . $tag['name'] . '</span></div>';
                                }
                            ?>
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
                        <div class="form__image-preview">
                            <?php
                            foreach ($data['post']['images'] as $image) {
                                echo '<img src="' . $image['url'] . '">';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="form__button-group">
                        <input type="submit" class="form__button--success" value="Create">
                        <input type="button" class="form__button--cancel" value="Cancel">
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
    <?php
    foreach ($data['tags'] as $tag) {
        echo 'tagSelectedArr.push(' . $tag['id'] . ');';
    }
    ?>
    let insertedTags = document.querySelectorAll('.form__tag-group');
    console.log(insertedTags);
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
        window.location.href = '/tag/create';
    })
</script>
</html>