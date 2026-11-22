<!doctype html>
<html lang="en">
    <head>
        <title>Log In</title>
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
        <link href="styles.css" rel="stylessheet">
    </head>

    <body>
        <header>
            <!-- place navbar here -->
        </header>
        <main>  
            <nav class="nav justify-content-center bg-info">
                <h1 class="display-1 text-white">Login</h1>
            </nav>
            <div class="container">
                <?php if ($error['alert'] != '') {echo "<div class='alert alert-danger my-3'>" . $error['alert'] ."</div>"; } ?>
                <form method="post" action="">
                    <div>
                        <div class="my-3 form-floating">
                            <input type="text" name="username" value="<?php $input['user']; ?>" class="form-control">
                            <label for="username">Username:</label>
                           <?php if ($error['user']) {
                            echo "<div class='alert alert-danger mt-1'>Username Required!</div>";
                           } ?>
                        </div>
                        <div class="my-3 form-floating">
                            <input type="password" name="password" value="<?php echo $input['pass']; ?>" class="form-control">
                            <label for="password">Password:</label>
                            <?php if ($error['pass']) {
                            echo "<div class='alert alert-danger mt-1'>Password Required!</div>";
                           } ?>
                        </div>

                        <input type="submit" name="submit" value="submit" class="btn btn-primary">
                    </div>
                </form>
            </div>

            <div class="container my-4">
                <a href="./reset_password.php">Forgot Password</a>
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
