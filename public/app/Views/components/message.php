<?php
if (isset($message['type'])) {
    switch ($message['type']) {
        case 'error': {
            echo "<div class=\"form__message form__message--error\" onclick='this.style.display=\"none\"'>" . $message['message'] . "</div>";
            break;
        }
        case 'success': {
            echo "<div class=\"form__message form__message--success\" onclick='this.style.display=\"none\"'>" . $message['message'] . "</div>";
            break;
        }
        case 'warning': {
            echo "<div class=\"form__message form__message--warning\" onclick='this.style.display=\"none\"'>" . $message['message'] . "</div>";
            break;
        }
    }
}
