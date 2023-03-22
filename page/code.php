<?php
session_start();
include '../include/connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT title FROM configuration WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $title = $row['title'];
} else {
    $title = "CodeExpress";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        <?php include '../style.css'; ?>
    </style>
    <?php
    $stmt = $pdo->prepare("SELECT * FROM configuration WHERE id = ?");
    $stmt->execute([$id]);

    $code = $stmt->fetch(PDO::FETCH_OBJ);

    function getTitle()
    {
        global $code;
        return $code->title;
    }

    function getCategory()
    {
        global $code;
        return $code->category;
    }

    function getDatum()
    {
        global $code;
        return $code->date;
    }

    function getCode()
    {
        global $code;
        return htmlspecialchars($code->code);
    }

    function getDescription()
    {
        global $code;
        return $code->description;
    }

    if (isset($_POST['title'])) {
        $title = htmlspecialchars($_POST['title']);
    }
    ?>
    <title><?php echo $title ?> - CodeExpress</title>
</head>

<body>

    <!-- Navbar -->
    <?php include "../include/navbar.php" ?>

    <!-- Main Content -->
    <div class="code-container">
        <div class="left-container">
            <h1 class="config"><?php echo getTitle(); ?></h1>
            <div class="header-box">
                <h2 class="margin"><?php echo getCategory(); ?></h2>
                <h2 class="margin"><?php echo getDatum(); ?></h2>
                <button id="copy-btn" onclick="copyToClipboard()">Copy Code</button>
            </div>
            <h1>Code:</h1>
            <div id="code">
                <?php echo getCode(); ?>
            </div>

            <div>
                <h1 class="config">Comments</h1>
                <form method="post">
                    <div class="flex-direction-column">
                        <input class="code-input" type="text" id="comment_text" name="comment_text" placeholder="Write your comment here">
                        <input class="code-button" type="submit" name="submit" value="Add Comment">
                    </div>
                    <?php
                    // Connect to the SQL database
                    $conn = mysqli_connect("localhost", "root", "", "codeexpress");

                    // Check connection
                    if (!$conn) {
                        die("Connection failed: " . mysqli_connect_error());
                    }

                    // Check if the form has been submitted
                    if (isset($_POST['submit'])) {
                        // Get the comment text
                        $comment_text = $_POST['comment_text'];
                        // Generate a random username or leave it blank
                        $username = rand(1, 9999);

                        // Insert the comment into the SQL table
                        $sql = "INSERT INTO comments (comment_text, username, date_created) VALUES ('$comment_text', '$username', NOW())";
                        if (mysqli_query($conn, $sql)) {
                        } else {
                            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                        }
                    }

                    // Retrieve the comments from the SQL table
                    $sql = "SELECT * FROM comments";
                    $result = mysqli_query($conn, $sql);

                    // Loop through the comments and display each one
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<p>Anonymous user " . $row['username'] . " said: " . $row['comment_text'] . " | on " . date("F j, Y, g:i a", strtotime($row['date_created'])) . "</p>";
                    }

                    // Close the SQL connection
                    mysqli_close($conn);
                    ?>
                </form>
            </div>
        </div>
        <div class="right-container">
            <h1>Post History</h1>
            <div class="right-container-content">
                <div class="line">
                    <div class="flex-direction-row-height">
                        <p class="margin-righter">Title</p>
                        <p>Category</p>
                    </div>
                </div>
                <div class="table-container">
                    <table>
                        <?php
                        // Retrieve data from the configuration table
                        $sql = "SELECT * FROM configuration";
                        $result = $pdo->query($sql);

                        if ($result->rowCount() > 0) {
                            while ($row = $result->fetch()) {
                                echo "<tr>";
                                echo "<td><a class='margin-right' href='../page/code.php?id=" . $row['id'] . "'>" . $row["title"] . "</a></td>";
                                echo "<td>" . $row["category"] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='2'>No results found</td></tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <!-- Footer -->
    <?php include "../include/footer.php" ?>

</body>

<script>
    // The function for copying code
    function copyToClipboard() {
        var codeDiv = document.getElementById("code");
        var codeText = codeDiv.innerHTML;
        navigator.clipboard.writeText(codeText).then(function() {
            alert("Code copied to clipboard!");
        }, function() {
            alert("Failed to copy code to clipboard.");
        });
    }
</script>

</html>