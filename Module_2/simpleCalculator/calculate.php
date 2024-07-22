<!DOCTYPE html>
<html>
<head><title>Result</title></head>
<body>
<?php
$first = (float)$_GET['firstNum'];
$second = (float)$_GET['secondNum'];
$operation = $_GET['operation'];
$result = 0;

// Check for division by zero
if ($operation == "division" && $second == 0) {
    echo "<p>Error: Division by zero is not allowed.</p>\n";
} else {
    switch ($operation) {
        case "addition":
            $result = $first + $second;
            break;
        case "subtraction":
            $result = $first - $second;
            break;
        case "multiplication":
            $result = $first * $second;
            break;
        case "division":
            $result = $first / $second;
            break;
        default:
            echo "<p>Invalid operation.</p>\n";
            break;
    }
    printf("<p>Result: %.3f</p>\n", $result);
}
?>
</body>
</html>
