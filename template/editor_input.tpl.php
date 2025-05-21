<?php function draw_editor_inputs($user) { ?>
    <?php
        $name=$user->name;
        $description=$user->description;
        $password=$user->password;
    ?>
    <section class="labeled-input">
        <h3>Your name</h3>
        <input class="card" type="textarea" name="title" placeholder="Write your Title.." value="<?=htmlspecialchars($name)?>">
    </section>

    <section class="labeled-input">
        <h3>Your Description</h3>
        <textarea class="card" name="description" rows="7" placeholder="Write your Description..."><?=htmlspecialchars($description)?></textarea>
    </section>

    <section class="labeled-input">
        <h3>Your Password</h3>
        <input class="card" type="password" name="password" placeholder="(Optional)Change your Password.." value="">
    </section>

    <input type="file" name="image"> 

<?php } ?>

<?php function draw_pubs_button() { ?>
    <div>
        <button type="submit" class="green-button" form="serviceForm">Confirm</button>
    </div>
<?php } ?>
