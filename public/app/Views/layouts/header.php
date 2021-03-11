<header class="header">
    <div class="header__wrapper">
        <div class="header__logo">Logo</div>
        <div class="header__nav">
            <a href="/" class="header__nav-item">Home</a>
            <?php
            if (AuthHelper::checkAuth()) {
                echo '<a href="/posts" class="header__nav-item">My Post</a>';
                echo '<a href="/auth/logout" class="header__nav-item">Logout</a>';
            } else {
                echo '<a href="/auth/login" class="header__nav-item">Login</a>';
            }
            ?>
        </div>
    </div>
</header>
