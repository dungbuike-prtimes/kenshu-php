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
                <?php
                include_once __DIR__."/../components/message.php";
                ?>
                <div class="form__field">
                    <a href="/posts/create" type="button" class="form__button--success --pull-right">+ New Post</a>
                </div>
                <ul class="content-container__list">
                    <?php
                    if (!isset($data['posts'])) {
                        echo "<p>You have no post.</p>";
                    }
                    foreach ($data['posts'] as $post) {
                        echo '<li class="post">
                        <a class="post__title" href="/posts/' . $post["id"] . '">' . h($post["title"]) . '</a>
                        <span class="post__content">' . h($post["content"]) .'</span>
                        <div class="post__tag-box">';
                        foreach ($post['tags'] as $tag) {
                            echo '<span class="form__tag">' . h($tag) . '</span>';
                        }
                        echo '</div></li>';
                    }
                    ?>
                </ul>
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