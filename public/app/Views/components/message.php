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
