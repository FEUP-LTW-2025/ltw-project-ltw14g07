<?php
    declare(strict_types=1);
    session_start();
?>

<?php function draw_header($id) { ?>
    <!DOCTYPE html>
    <html>
        <head>
            <title>Easy Code</title>
            <meta charset="utf-8">
            <link rel="stylesheet" href="../css/style.css">
            <link rel="stylesheet" href="../css/layout.css">
            <link rel="stylesheet" href="../css/responsive.css">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
        </head>
        <body>
            <header>
                <div>
                    <h1><a href="index.php">Easy Code</a></h1>
                    <section class="spaced wrap-list">
                        <h3>Let programmers work for you</h3>

                        <?php
                        if (!empty($_SESSION['userID'])) {
                            draw_loggedIn();
                        } else draw_signup();
                        ?>
                    </section>
                    <nav class="wrap-list">
                        <a href="/pages/index.php">Main</a>
                        <?php
                        if (!empty($_SESSION['userID']))
                            draw_loggedIn_nav();
                        ?>
                    </nav>
                </div>
            </header>
            <main id='<?=$id?>'>
<?php } ?>



<?php function draw_footer() { ?>
            </main>
        </body>
    </html>
<?php } ?>



<?php function draw_signup() { ?>
     <div id="signup">
        <a href="/pages/signup.php?q=r">Register</a>
        <a href="/pages/signup.php?q=l">Login</a>
    </div>
<?php } ?>


<?php function draw_loggedIn() { ?>
     <div id="signup">
        <form action="/../action/actionLogout.php">
            <button type="submit">Logout</button>
        </form>
    </div>
<?php } ?>

<?php function draw_loggedIn_nav() { ?>
    <a href="/pages/profile.php">Profile</a>
    <a href="/pages/createService.php">Create Service</a>  
    <a href="/pages/manageServices.php">Manage</a>  
<?php } ?>