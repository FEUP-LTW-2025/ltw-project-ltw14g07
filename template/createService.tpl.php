
<?php
    require_once(__DIR__ . '/../template/input.tpl.php');
    require_once(__DIR__ . '/../template/filters.tpl.php');
?>


<?php function draw_form() { ?>
    <h1 class="title">Create and Publish your own Service</h1>
    <form id="serviceForm">
        <?php 
            draw_text_inputs(); 
            draw_hor_filters();
        ?>
    </form>    
    <?php 
    draw_pub_button();
} ?>