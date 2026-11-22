<!doctype html>
<html lang="en">
    <head>
        <title>Reset Password</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />

        <!-- Bootstrap CSS v5.2.1 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
    </head>

    <body>
        <header>
            <!-- place navbar here -->
        </header>
        <main>
            <div>
                <nav class="nav justify-content-center bg-info">
                    <h1 class="display-1 text-white">Reset Password</h1>
                </nav>
            </div>

            <div class="container">
                <?php if ($error['alert'] != '') {echo "<div class='alert alert-danger my-3'>".$error['alert'] ."</div>"; } ?>
                <?php if ($success['alert'] != '') {echo "<div class='alert alert-success my-3'>" . $success['alert'] . "</div>"; }?>
                <form method="post">
                    <div>
                    <div class="my-3 form-floating">
                            <input type="email" name="email" value="<?php echo $input['email']; ?>" class="form-control">
                            <label for="email">Email:</label>
                            <?php if ($error['email'] != '') {
                            echo $error['email'];
                           } ?>
                        </div>
                        <div class="my-3 form-floating">
                            <input type="password" name="password" value="<?php echo $input['pass']; ?>" class="form-control">
                            <label for="password">Create Password:</label>
                            <?php if ($error['pass'] != '') {
                            echo $error['pass'];
                           } ?>
                        </div>

                            <div class="my-3 form-floating">
                            <input type="password" name="password2" value="<?php echo $input['pass2']; ?>" class="form-control">
                            <label for="password2">Re-type Password:</label>
                            <?php if ($error['pass2'] != '') {
                            echo $error['pass2'];
                           } ?>
                        </div>

                        <input type="submit" name="submit" value="submit" class="btn btn-primary">
                    </div>
                </form>

                <div class="container my-3">
                    <a href="index.php">Login</a>
                </div>
            </div>
        </main>
        <footer>
            <!-- place footer here -->
        </footer>
        <!-- Bootstrap JavaScript Libraries -->
        <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"
        ></script>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"
        ></script>
    </body>
</html>