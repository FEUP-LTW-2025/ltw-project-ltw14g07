
<?php
    require_once(__DIR__ . '/../template/input.tpl.php');
    require_once(__DIR__ . '/../template/filters.tpl.php');
?>


<?php function draw_createService_page($service, $filters, $userID) { ?>
    <?php
    $label = (isset($service)) ? "Edit your Service" : "Create and Publish your own Service";
    ?>
    <h1><?=$label?></h1>
    <?php
    draw_form($service, $filters, $userID);
    ?>
<?php } ?>


<?php function draw_form($service, $filters, $userID) { ?>
    <form id="serviceForm" action="/../action/actionCreateService.php" method="post" enctype="multipart/form-data">
        <?php 
            draw_text_inputs($service); 
            draw_hor_filters($filters, $service);

            $label = isset($service) ? 'Save' : 'Publish';
            $path = __DIR__ . "/../images/service/$service->serviceID.jpg";
            $src = "/../images/service/$service->serviceID.jpg";
        ?>

        <?php if (file_exists($path)): //temp style down?>
            <h3>Previous Attached Image:</h3>
            <img src="<?=$src?>" style="max-width: 20em;">
        <?php endif; ?>

        <input type="file" name="image"> 

        <input type="hidden" value="<?=$userID?>" name="userID">    <!-- temporary until login --> 
        <input type="hidden" name="serviceID" value=<?=$service->serviceID?>>
        <button class="green-button" type="submit"><?=$label?></button>
    </form>    
<?php } ?>