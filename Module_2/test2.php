<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculation Result</title>
</head>
<body>
    <h1>Calculation Result</h1>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['firstNum'], $_GET['secondNum'], $_GET['operation'])) {
        $first = (float)$_GET['firstNum'];
        $second = (float)$_GET['secondNum'];
        $operation = $_GET['operation'];
        $result = null;
        $error = null;

        // Check for division by zero
        if ($operation == "division" && $second == 0) {
            $error = "Error: Division by zero is not allowed.";
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
                    $error = "Invalid operation.";
                    break;
            }
        }

        if ($error) {
            echo "<p>$error</p>\n";
        } else {
            printf("<p>Result: $%.2f</p>\n", $result);
        }
    } else {
        echo "<p>No calculation requested.</p>\n";
    }
    ?>
</body>
</html>
