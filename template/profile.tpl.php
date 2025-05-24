

<?php function draw_profile_resume($user) { ?>
    <?php
    $description = $user->description ? $user->description : "No user description"
    ?>
    <section class='service'>
        <aside>
            <?php   
            draw_profile_pic($user->userID);
            ?>
            <p><?=htmlspecialchars($user->name)?></p>
        </aside>
        <article>
            <div class="card">
                <p><?=htmlspecialchars($description)?></p>
            </div>
        </article>
    </section>
<?php } ?> 

<?php function draw_profile_pic($userID)  { 
        $path = __DIR__ . "/../images/profile/$userID.jpg";
        if (!file_exists($path)) {
            $src = "/../images/profile/default.jpg";
        }
        else $src = "/../images/profile/$userID.jpg";
        ?>
        <img src=<?=$src?> >
 <?php }  ?>


<?php function draw_edit_profile()  { ?>
    <section class="right">
        <a class="green-button" href="profile_editor.php">Edit Profile</a>
    </section>
 <?php }  ?>