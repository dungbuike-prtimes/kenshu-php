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

                <form id="form" class="form" method="post" action="/post/create" enctype="multipart/form-data">
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
                        </div>
                        <select id="tagSelect" class="form__input form__input--select2">
                            <option value="1">akjdh</option>
                            <option value="2">akjdh</option>
                            <option value="3">akjdh</option>
                            <option value="4">akjdh</option>
                            <option value="5">akjdh</option>
                        </select>
                    </div>
                    <div class="form__field">
                        <label class="form__label" for="file-upload">Image</label>
                        <input id="file-upload" type="file" class="form__file-upload" multiple name="images[]"
                               placeholder="Upload Image">
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
    tagSelect.addEventListener('change', () => {
        let val = tagSelect.value;
        let tagGroup = document.createElement('div');

        let tagValue = document.createElement('input');
        tagValue.hidden = true;
        tagValue.value = val;

        let tag = document.createElement('span');
        tag.classList.add('form__tag');
        tag.textContent = val;
        tagField.appendChild(tag);
        tag.addEventListener('click', () => {
            tag.remove();
        })
    })
</script>
</html>