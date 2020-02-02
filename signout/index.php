<html>
    <?php
        setCookie("userID", $user_input, time() -3600, "/");
        header("Location: ../");
    ?>
</html>