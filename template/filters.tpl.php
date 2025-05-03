<?php function draw_vert_filters($filters) { ?>
    <aside class="card">
        <h1>Filters</h1>
        <form>
           <?php 
            draw_fields($filters->fields);
            draw_languages($filters->languages);
            ?>

            <section class="labeled-input">
                <h3>Hourly Rate ($/h)</h3>
                <div class="wrap-list">
                    <label><input type="radio" name="hourlyRate"> 1$-5$</label>
                    <label><input type="radio" name="hourlyRate"> 6$-10$</label>
                    <label><input type="radio" name="hourlyRate"> 11$-15$</label>
                    <label><input type="radio" name="hourlyRate"> 16$-20$</label>
                </div>
            </section>

            <section class="labeled-input">
                <h3>Delivery Time (weeks)</h3>
                <div class="wrap-list">
                    <label><input type="radio" name="deliveryTime"> 1-2 weeks</label>
                    <label><input type="radio" name="deliveryTime"> 3-4 weeks</label>
                </div>
            </section>
        </form>
    </aside>
<?php } ?>


<?php function draw_hor_filters($filters, $service = null) { ?>
    <?php
    $hr = 5;
    $dt = 1;
    if (isset($service)) {
        $hr = $service->hourlyRate;
        $dt = $service->deliveryTime;
    }
    ?>

    <div class='spaced'>
        <?php
        draw_fields($filters->fields, $service->fields, 'card');
        draw_languages($filters->languages, $service->languages, 'card');
        ?>
    </div>
    <div class='spaced'>
        <section class="labeled-input">
            <h3>Hourly Rate ($/h)</h3>
            <input type="number" name="hourlyRate" value=<?=$hr?>>
        </section>

        <section class="labeled-input">
            <h3>Delivery Time (weeks)</h3>
            <input type="number" name="deliveryTime" value="<?=$dt?>"> 
        </section>  
    </div>
<?php } ?>



<?php function draw_fields($fields, $checkedFields=null, $class="") { ?>
    <section class="labeled-input">
        <h3>Fields</h3>
        <div class='<?=$class?> wrap-list'>
            <?php foreach ($fields as $field) { ?>
                <?php 
                $checked="";
                if (isset($checkedFields)) {
                    $checked = in_array($field, $checkedFields);
                    $checked = $checked ? "checked" : "false";
                }
                ?>
                <label><input type="checkbox" name="fields[]" value="<?=$field?>" <?=$checked?>> <?=$field?> </label>
            <?php } ?>
        </div>
    </section>
<?php } ?>


<?php function draw_languages($languages, $checkedLanguages=null, $class="") { ?>
    <section class="labeled-input">
        <h3>Languages</h3>
        <div class='<?=$class?> wrap-list'>
            <?php foreach ($languages as $language) { ?>
                <?php 
                $checked="";
                if (isset($checkedLanguages)) {
                    $checked = in_array($language, $checkedLanguages);
                    $checked = $checked ? "checked" : "false";
                }
                ?>
                <label><input type="checkbox" name="languages[]" value="<?=$language?>" <?=$checked?>> <?=$language?> </label>
            <?php } ?>
        </div>
    </section>
<?php } ?>

