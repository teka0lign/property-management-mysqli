<?php
// Simple smoke test (non-exhaustive)
// Run: php tests/simple_test.php
echo \"Running simple checks...\\n\";
if (!file_exists(__DIR__ . '/../public/api.php')) {
    echo \"ERROR: public/api.php not found\\n\";
    exit(1);
}
echo \"Files exist. Please set up database and run the built-in server for manual tests.\\n\";
