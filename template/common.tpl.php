<?php
    declare(strict_types=1);
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
            <script src="../js/click.js" defer></script>
        </head>
        <body>
            <header>
                <div>
                    <h1><a href="index.php">Placeholder</ahref></h1>
                    <section class="spaced">
                        <h3>Let programmers work for you</h3>
                        <div id="signup">
                            <a>Register</a>
                            <a>Login</a>
                        </div>
                    </section>
                </div>
            </header>
            <main id='<?=$id?>'>
<?php } ?>



<?php function draw_footer() { ?>
            </main>
            <a href='/pages/createService.php'>service </a>
            <a href="/pages/service.php?id=1">select service </a>
            <a href="/pages/index.php">go to main </a>
            <a href ="/pages/profile.php">  got to profile</a>
        </body>
    </html>
<?php } ?>