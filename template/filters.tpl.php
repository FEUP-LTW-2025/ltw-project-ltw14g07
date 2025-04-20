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


<?php function draw_hor_filters() { ?>
    <div>
        <section class="labeled-input">
            <h3 class="med-label">Fields</h3>
            <div class="card">
                <label><input type="checkbox"> UI/UX</label>
                <label><input type="checkbox">Game Development</label>
                <label><input type="checkbox"> Neural Network </label>
                <label><input type="checkbox"> Graphics Programming</label>
                <label><input type="checkbox"> Cybersecurity </label>
                <label><input type="checkbox"> Compiler</label>
                <label><input type="checkbox"> Kernel</label>
                <label><input type="checkbox"> Systems Programming</label>
                <label><input type="checkbox"> Full Stack</label>
                <label><input type="checkbox"> Backend</label>
            </div>
        </section>

        <section class="labeled-input">
            <h3 class="med-label">Languages</h3>
            <div class="card">
                <label><input type="checkbox"> JavaScript</label>
                <label><input type="checkbox"> Python</label>
                <label><input type="checkbox"> TypeScript</label>
                <label><input type="checkbox"> Ruby</label>
                <label><input type="checkbox"> Java</label>
                <label><input type="checkbox"> PHP</label>
                <label><input type="checkbox"> HTML</label>
                <label><input type="checkbox"> CSS</label>
                <label><input type="checkbox"> React</label>
                <label><input type="checkbox"> SQL</label>
            </div>
        </section>
    </div>

    <div>
        <section class="labeled-input">
            <h3 class="med-label">Hourly Rate ($/h)</h3>
            <input type="number" value="5">
        </section>

        <section class="labeled-input">
            <h3 class="med-label">Delivery Time (weeks)</h3>
            <input type="number" value="1"> 
        </section>  
    </div>
<?php } ?>



<?php function draw_fields($fields) { ?>
    <section class="labeled-input">
        <h3 class="med-label">Fields</h3>
        <div>
            <?php foreach ($fields as $field) { ?>
                <label><input type="checkbox"><?=$field?></label>
            <?php } ?>
        </div>
    </section>
<?php } ?>

<?php function draw_languages($languages) { ?>
    <section class="labeled-input">
        <h3 class="med-label">Fields</h3>
        <div>
            <?php foreach ($languages as $language) { ?>
                <label><input type="checkbox"><?=$language?></label>
            <?php } ?>
        </div>
    </section>
<?php } ?>

