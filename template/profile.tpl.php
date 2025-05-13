

<?php function draw_profile_resume($user) { ?>
    <section class='service'>
        <aside>
            <img src="https://picsum.photos/id/237/200/300">
            <p><?=$user->name?></p>
        </aside>
        <article>
            <div class="card">
                <p><?=$user->description?></p>
            </div>
        </article>
    </section>

    <a href="profile_editor.php">Edit Profile</a>

<?php } 





?> 
