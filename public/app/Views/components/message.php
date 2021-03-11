<?php
if (isset($_SESSION['type'])) {
    switch ($_SESSION['type']) {
        case 'error': {
            echo "<div class=\"form__message form__message--error\" onclick='this.style.display=\"none\"'>" . $_SESSION['message'] . "</div>";
            break;
        }
        case 'success': {
            echo "<div class=\"form__message form__message--success\" onclick='this.style.display=\"none\"'>" . $_SESSION['message'] . "</div>";
            break;
        }
        case 'warning': {
            echo "<div class=\"form__message form__message--warning\" onclick='this.style.display=\"none\"'>" . $_SESSION['message'] . "</div>";
            break;
        }
    }

    unset($_SESSION['type']);
    unset($_SESSION['status']);
    unset($_SESSION['message']);

}
