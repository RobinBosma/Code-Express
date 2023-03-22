<?php
session_start();
include "../include/connection.php";
include "../include/insert-post.php";
?>

<!DOCTYPE html>
<html>

<head>
    <title>Code Express</title>
    <link rel="icon" href="../img/logo.png" type="image/x-icon" />

    <!-- Include CodeMirror Stylesheet -->
    <link rel="stylesheet" href="https://codemirror.net/theme/dracula.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/codemirror.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/theme/darcula.min.css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/mode/xml/xml.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/mode/css/css.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/mode/htmlmixed/htmlmixed.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/mode/php/php.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/mode/sql/sql.min.js"></script>

    <!-- Include Your Custom Stylesheet -->
    <style>
        <?php include '../style.css'; ?>
    </style>
</head>

<body>
    <!-- Navbar -->
    <div class="item">
        <?php include "../include/navbar.php" ?>
    </div>

    <div class="grid-container">
        <div class="item1">
            <form method="post" action="../page/index.php">
                <h1 class="config-code">Code:</h1>

                <!-- Add the Textarea Element for Code -->
                <textarea cols="80" rows="10" id="code" type="text" name="code" mode="text/html"></textarea>
                <!-- Initialize CodeMirror on the Textarea Element with Multiple Modes -->
                <script>
                    var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
                        lineNumbers: true,
                        mode: "htmlmixed",
                        theme: "darcula"
                    });

                    var modes = {
                        "HTML": "htmlmixed",
                        "CSS": "css",
                        "JavaScript": "javascript",
                        "PHP": "php",
                        "Python": "python",
                        "Ruby": "ruby",
                        "Java": "clike",
                        "C++": "clike",
                        "C": "clike",
                        "SQL": "sql"
                    };

                    var modeSelect = document.getElementById("mode-select");

                    for (var mode in modes) {
                        var option = document.createElement("option");
                        option.setAttribute("value", modes[mode]);
                        option.innerHTML = mode;
                        modeSelect.appendChild(option);
                    }

                    modeSelect.addEventListener("change", function() {
                        var mode = modeSelect.options[modeSelect.selectedIndex].value;
                        editor.setOption("mode", mode);
                    });
                </script>
            </form>
            <h1 class="config">Configurations</h1>
        </div>
        <div class="item2">
            <div class="configurations">
                <p class="config-title">Title:</p>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="configurations">
                <p class="config-created">Created by:</p>
                <input type="text" id="created" name="creator">
            </div>

            <div class="category" required>
                <p class="config-category">Category:</p>
                <div id="checkboxes" required>
                    <div class="checkboxes-top" required>
                        <input type="checkbox" id="checkbox-codes" name="category[]" value="HTML">
                        <label for="HTML">HTML</label><br>
                        <input type="checkbox" id="checkbox-codes" name="category[]" value="CSS">
                        <label for="CSS">CSS</label><br>
                        <input type="checkbox" id="checkbox-codes" name="category[]" value="PHP">
                        <label for="PHP">PHP</label><br>
                    </div>
                    <div class="checkboxes-bottom">
                        <input type="checkbox" id="checkbox-codes" name="category[]" value="JavaScript">
                        <label for="JavaScript">JavaScript</label><br>
                        <input type="checkbox" id="checkbox-codes" name="category[]" value="SQL">
                        <label for="SQL">SQL</label><br>
                    </div>
                </div>
            </div>
        </div>
        <div class="item3-text">
            <h1 class="config-desc">Description:</h1>
        </div>
        <div class="item3">
            <textarea cols="80" rows="10" id="desc" type="text" name="description">
</textarea>
        </div>
        <div class="item4">
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
        <div class="item5">
            <input type="submit" id="submit-public" name="submit-public" href="code.php" value="Create Public post"> <input type="submit" id="submit-private" name="submit-private" value="Create Private post">
        </div>
        </form>
        <tbody>
        </tbody>
        </table>
        <!-- Footer -->
        <div class="item7">
            <?php include "../include/footer.php" ?>
        </div>
</body>

</html>