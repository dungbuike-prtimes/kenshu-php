<div class="side-container">
    <div class="side-container__header">
        <h2>Side Content</h2>
    </div>
    <div class="side-container__body">
        <h3 class="username"><?php echo(h($_SESSION['user']['username']));?></h3>
        <p class="email"><?php echo(h($_SESSION['user']['email']));?></p>
    </div>
</div>
