<?php function draw_vert_filters($filters) { ?>
    <aside class="card">
        <form>
           <?php 
            draw_fields($filters->fields);
            draw_languages($filters->languages);
            ?>

            <section class="labeled-input">
                <h3 class="med-label">Hourly Rate ($/h)</h3>
                <div>
                    <label><input type="radio" name="hourlyRate"> 1$-5$</label>
                    <label><input type="radio" name="hourlyRate"> 6$-10$</label>
                    <label><input type="radio" name="hourlyRate"> 11$-15$</label>
                    <label><input type="radio" name="hourlyRate"> 16$-20$</label>
                </div>
            </section>

            <section class="labeled-input">
                <h3 class="med-label">Delivery Time (weeks)</h3>
                <div>
                    <label><input type="radio" name="deliveryTime"> 1-2 weeks</label>
                    <label><input type="radio" name="deliveryTime"> 3-4 weeks</label>
                </div>
            </section>
        </form>
    </aside>
<?php } ?>


<?php function draw_hor_filters($filters) { ?>
    <div>
        <?php
        draw_fields($filters->fields, 'card');
        draw_languages($filters->languages, 'card');
        ?>
    </div>
    <div>
        <section class="labeled-input">
            <h3 class="med-label">Hourly Rate ($/h)</h3>
            <input type="number" name="hourlyRate" value="5">
        </section>

        <section class="labeled-input">
            <h3 class="med-label">Delivery Time (weeks)</h3>
            <input type="number" name="deliveryTime" value="1"> 
        </section>  
    </div>
<?php } ?>



<?php function draw_fields($fields, $class="") { ?>
    <section class="labeled-input">
        <h3 class="med-label">Fields</h3>
        <div class=<?=$class?>>
            <?php foreach ($fields as $field) { ?>
                <label><input type="checkbox" name="fields[]" value="<?=$field?>"> <?=$field?> </label>
            <?php } ?>
        </div>
    </section>
<?php } ?>

<?php function draw_languages($languages, $class="") { ?>
    <section class="labeled-input">
        <h3 class="med-label">Languages</h3>
        <div class=<?=$class?>>
            <?php foreach ($languages as $language) { ?>
                <label><input type="checkbox" name="languages[]" value="<?=$language?>"> <?=$language?> </label>
            <?php } ?>
        </div>
    </section>
<?php } ?>

