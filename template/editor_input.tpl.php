<?php function draw_editor_inputs($user) { ?>
    <?php
        $name=$user->name;
        $description=$user->description;
    ?>
    <section class="labeled-input">
        <h3>Your name</h3>
        <input class="card" type="textarea" name="title" placeholder="Write your Title.." value="<?=htmlspecialchars($name)?>">
    </section>

    <section class="labeled-input">
        <h3>Your Description</h3>
        <textarea class="card" name="description" rows="7" placeholder="Write your Description..."><?=htmlspecialchars($description)?></textarea>
    </section>
<?php } ?>

<?php function draw_pubs_button() { ?>
    <div>
        <button type="submit" class="green-button" form="serviceForm">Confirm</button>
    </div>
<?php } ?>
