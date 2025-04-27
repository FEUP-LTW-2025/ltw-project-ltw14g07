<?php function draw_text_inputs($data = null) { ?>
    <?php
        $title='';
        $description='';
        if (isset($data)) {
            $title = $data->title;
            $description = $data->description;
        }

    ?>
    <section class="labeled-input">
        <h3>Title</h3>
        <input class="card" type="textarea" name="title" placeholder="Write your Title.." value="<?=$title?>">
    </section>

    <section class="labeled-input">
        <h3>Description</h3>
        <textarea class="card" name="description" rows="7" placeholder="Write your Description..."><?=$description?></textarea>
    </section>
<?php } ?>

<?php function draw_pub_button() { ?>
    <div>
        <button type="submit" class="green-button" form="serviceForm">Publish</button>
    </div>
<?php } ?>
