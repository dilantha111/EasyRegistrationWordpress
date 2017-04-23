<?php
session_start();
session_destroy();
header("Location: ../message.php?info_logout=1");