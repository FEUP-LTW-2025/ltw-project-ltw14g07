
<?php
    require_once(__DIR__ . '/../template/input.tpl.php');
    require_once(__DIR__ . '/../template/filters.tpl.php');
?>


<?php function draw_form($filters, $userID) { ?>
    <h1>Create and Publish your own Service</h1>
    <form id="serviceForm" action="/../action/actionCreateService.php" method="post">
        <?php 
            draw_text_inputs(); 
            draw_hor_filters($filters);
        ?>
        <input type="hidden" value="<?=$userID?>" name="userID">
    </form>    
    <?php 
    draw_pub_button();
} ?>