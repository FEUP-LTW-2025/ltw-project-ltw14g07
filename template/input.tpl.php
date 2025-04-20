<?php function draw_text_inputs() { ?>
    <section class="labeled-input">
        <h3 class="med-label">Title</h3>
        <input class="card" type="textarea" name="title" placeholder="Write your Title..">
    </section>

    <section class="labeled-input">
        <h3 class="med-label">Description</h3>
        <textarea class="card" name="description" rows="7" placeholder="Write your Description..."></textarea>
    </section>
<?php } ?>

<?php function draw_pub_button() { ?>
    <div>
        <button type="submit" class="green-button" form="serviceForm">Publish</button>
    </div>
<?php } ?>
