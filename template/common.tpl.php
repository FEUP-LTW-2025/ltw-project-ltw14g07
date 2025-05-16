<?php
    declare(strict_types=1);
    session_start();
?>

<?php function draw_header($id) { ?>
    <!DOCTYPE html>
    <html>
        <head>
            <title>Placeholder</title>
            <meta charset="utf-8">
            <link rel="stylesheet" href="../css/style.css">
            <link rel="stylesheet" href="../css/layout.css">
            <link rel="stylesheet" href="../css/responsive.css">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
        </head>
        <body>
            <header>
                <div>
                    <h1><a href="index.php">Placeholder</a></h1>
                    <section class="spaced">
                        <h3>Let programmers work for you</h3>
                        <?php
                        if (!empty($_SESSION['userID'])) {
                            draw_loggedIn();
                        } else draw_signup();
                        ?>
                    </section>
                </div>
            </header>
            <main id='<?=$id?>'>
<?php } ?>



<?php function draw_footer() { ?>
            </main>
            <a href='/pages/createService.php'>service </a>
            <a href="/pages/service.php?serviceID=1">select service </a>
            <a href="/pages/index.php">go to main </a>
            <a href ="/pages/profile.php">  got to profile</a>
            <a href ="/pages/manageServices.php">manage services</a>
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
        <a href="/pages/profile.php">Profile</a>
        <form action="/../action/actionLogout.php">
            <button class="red-button" type="submit">Logout</button>
        </form>
    </div>
<?php } ?>