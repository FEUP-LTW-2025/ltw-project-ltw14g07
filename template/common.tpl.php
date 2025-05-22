<?php
    declare(strict_types=1);
    require_once(__DIR__ . '/../utils/session.php');
?>

<?php function draw_header($id, Session $session) { ?>
    <!DOCTYPE html>
    <html>
        <head>
            <title>Easy_Code</title>
            <meta charset="utf-8">
            <link rel="stylesheet" href="../css/style.css">
            <link rel="stylesheet" href="../css/layout.css">
            <link rel="stylesheet" href="../css/responsive.css">
            <link rel="stylesheet" href="../css/login.css">
            <link rel="stylesheet" href="../css/register.css">
            <script src="../js/closeMessage.js" defer></script> 
            <script src="../js/click.js" defer></script> 
            <script src="../js/loadMore.js" defer></script> 
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        </head>
        <body>
            <div class="page-wrapper">
                <header>
                    <div>
                        <h1><a href="index.php">Easy_Code</a></h1>
                        <section class="spaced wrap-list">
                            <h3>Let programmers work for you</h3>

                            <?php
                            if ($session->isLoggedIn()) {
                                draw_loggedIn();
                            } else draw_signup();
                            ?>
                        </section>
                        <nav class="wrap-list">
                            <a href="/pages/index.php">Main</a>
                            <?php
                            if ($session->isLoggedIn())
                                draw_loggedIn_nav();
                            ?>
                        </nav>
                    </div>
                </header>

                <?php if (!empty($session->getMessages())):?>
                    <section id="messages">
                        <?php foreach ($session->getMessages() as $messsage) { ?>
                            <article class="<?=$messsage['type']?>">
                                <?=$messsage['text']?>
                            </article>
                        <?php } ?>
                    </section>
                <?php endif; ?>

            <main id='<?=$id?>'>
<?php } ?>



<?php function draw_footer() { ?>
            </main>
            <footer>
                <section>
                    <p>Easy_Code provides a website for safe transactions between programmers, specializing in providing custom coding solutions to your problems.</p>
                    <section class="spaced">
                        <label>Copyright &copy; 2025 Easy_Code. All rights reserved.</label>
                        <nav>
                            <a href="#">LinkedIn <i class="fa fa-linkedin-square"></i></a> |
                            <a href="#">GitHub <i class="fa fa-github-square"></i></a> |
                            <a href="#">Twitter <i class="fa fa-twitter-square"></i></a>
                        </nav>
                    </section>
                </section>
            </footer>
            </div>
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