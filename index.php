<?php

require_once __DIR__ . '/vendor/autoload.php';

ob_start();

require_once __DIR__ . '/template/_header.php';

require_once __DIR__ . '/template/mainpage.php';

require_once __DIR__ . '/template/_footer.php';

ob_end_flush();