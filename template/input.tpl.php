<?php function draw_text_inputs() { ?>
    <section class="labeled-input">
        <h3 class="med-label">Title</h3>
        <input class="card" type="textarea" placeholder="Write your Title..">
    </section>

    <section class="labeled-input">
        <h3 class="med-label">Description</h3>
        <textarea class="card" name="description" rows="7" placeholder="Write your Description..."></textarea>
    </section>
<?php } ?>

<?php function draw_pub_button() { ?>
    <div>
        <button class="green-button">Publish</button>
    </div>
<?php } ?>
