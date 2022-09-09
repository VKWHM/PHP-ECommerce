<?php
    session_start();

    if (!isset($_SESSION["Username"])) {
        header("Location: index.php");
        exit();
    }

    /*
     ** Manage Members Page
     ** You Can Add | Edit | Delete Members From Here
    */
    include "ini.php";
    $do = isset($_GET["do"]) ? $_GET["do"] : "Manage";

    switch ($do) {
        case "Add":
            echo "Add Section";
            break;
        case "Edit":
            // Check If Request has userid and is numaric & get integer value from of it
            $userid = isset($_GET["userid"]) && is_numeric($_GET['userid']) ? $_GET['userid'] : 0;
            $stmt = $db->prepare("SELECT * FROM users WHERE UserID = ?");
            $stmt->execute(array($userid));
            $row = $stmt->fetch();
            if ($stmt->rowCount() > 0) {

?>
                <h1 class="text-center">Edit Member</h1>

                <div class="container">

                    <form class="form-horziontal" action="?do=Update" method="POST">
                        <!--Hidden Input For User ID-->
                        <input type="hidden" name="userid" value="<?php echo $userid ?>">

                        <!--Start Username Input-->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 col-sm-offset-1 control-label" for="">Username</label>
                            <div class="col-sm-7">
                            <input class="form-control" type="text" name="username" value=<?php echo $row["Username"] ?> autocomplete="off">
                            </div>
                        </div>
                        <!--End Username Input-->

                        <!--Start Password Input-->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 col-sm-offset-1 control-label" for="">Password</label>
                            <div class="col-sm-7">
                                <input class="form-control" type="password" name="password" autocomplete="new-password">
                            </div>
                        </div>
                        <!--End Pasword Input-->

                        <!--Start Email Input-->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 col-sm-offset-1 control-label" for="">Email</label>
                            <div class="col-sm-7">
                                <input class="form-control" type="email" name="email" value=<?php echo $row["Email"] ?> autocomplete="off">
                            </div>
                        </div>
                        <!--End Email Input-->

                        <!--Start Full Name Input-->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 col-sm-offset-1 control-label" for="">Full Name</label>
                            <div class="col-sm-7">
                                <input class="form-control" type="text" name="fname" value=<?php echo $row["FullName"] ?> autocomplete="off">
                            </div>
                        </div>
                        <!--End Full Name Input-->

                        <!--Start Submit Buttom-->
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-7">
                                <input class="btn btn-primary btn-lg" type="submit" value="Update">
                            </div>
                        </div>
                        <!--End Submit Buttom-->
                    </form>
                </div>
            <?php
                } else {
                    echo "Theres No Such ID";
                }
                break;
        case "Delete":
            echo "Delete Section";
            break;
        case "Update":
            if ($_SERVER['REQUEST_METHOD'] === "POST") {

                // Get Post Item
                $id = $_POST["userid"];
                $username = $_POST["username"];
                $email = $_POST["email"];
                $fname = $_POST["fname"];
                $argument = array($username, $email, $fname, $id);
                $password = empty($_POST["password"]) ? False : sha1($_POST['password']);

                if ($password) {
                    $passQuery = " Password = ?,";
                    array_unshift($argument, $password);
                } else {
                    $passQuery = "";
                }
                // Update Data

                $stmt = $db->prepare("UPDATE users SET $passQuery Username = ?, Email = ?, FullName = ? WHERE UserID = ?");
                $stmt->execute($argument);
                echo $stmt->rowCount() . " Record Updated";

            } else {
                header("Location: members.php?do=Manage");
            }
            break;
        case "Manage":
        default:
            echo "Manage Section";
            break;
    }

    




    include $tpl . "footer.php";