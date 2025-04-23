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
                    <h1><a>Placeholder</a></h1>
                    <section>
                        <h3>Let programmers work for you</h3>
                        <div id="signup">
                            <a>Edit Profile</a>
                        </div>
                    </section>
                </div>
            </header>
            <main id='<?=$id?>'>
<?php } ?>

<?php function draw_profile_resume() { ?>
    <section>
        <aside>
            <img src="https://picsum.photos/id/237/200/300">
            <p>John Doe</p>
        </aside>
        <article>
            <h1 class="title">Hi, welcome to my profile page!</h1>
            <div class="card">
                <p>
                    As a UI UX designer, I put much value on trustful, transparent, long-term relationships. That's why I'm very accurate in performing a professional approach. Your privacy, terms, and deadlines will always be respected. 
                    All I need to start is your specifications, a description of a problem you face, or just an initial idea of the future design product. But in case you are not sure at all - no problem. We will work out the product's vision together, and I will provide you with fresh and unique ideas and efficient methods to create something outstanding and productive. I will manage your design project from start to final result. Feel free to contact me to discuss the details.
                </p>
            </div>
            <div>
                <section class="skills">
                    <article>
                        <h1>Fields:</h1>
                        <div class="tag-list">
                            <span>UI/UX</span>
                        </div>
                    </article>

                    <article>
                        <h1>Languages:</h1>
                        <div class="tag-list">
                            <span>PHP</span>
                            <span>HTML</span>
                            <span>CSS</span>
                            <span>JavaScript</span>
                        </div>
                    </article>
                    <article>
                        <h1>Carrer Time:</h1>
                        <div class="tag-list">
                            <span>1 year</span>
                        </div>
                    </article>
                    <article>
                        <h1>Carrer resume:</h1>
                        <div class="tag-list">
                            <span>15 projects</span>
                    <article>
                </section>
            </div>
        </article>

    </section>
<?php } ?> 



<?php function draw_footer() { ?>
            </main>
            <a href='/pages/createService.php'>create service (temporary)</a>
            <a href="/pages/service.php?id=1">select service (temporary)</a>
            <a href="/pages/index.php">go to main (temporary)</a>
            <a href ="/pages/profile.php">  got to profile</a>

        </body>
    </html>
<?php } ?>