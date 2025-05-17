
<?php
    require_once(__DIR__ . '/../template/input.tpl.php');
    require_once(__DIR__ . '/../template/filters.tpl.php');

    require_once(__DIR__ . '/../utils/session.php');
?>


<?php function draw_createService_page($service, $filters, Session $session) { ?>
    <?php
    $label = (isset($service)) ? "Edit your Service" : "Create and Publish your own Service";
    ?>
    <h1><?=$label?></h1>
    <?php
    draw_form($service, $filters, $session);
    ?>
<?php } ?>


<?php function draw_form($service, $filters, Session $session) { ?>
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

        <input type="hidden" name="serviceID" value=<?=$service->serviceID?>>
        <input type="hidden" name="csrf" value=<?=$session->getCsrf()?>>
        <div class="right">
            <button class="green-button" type="submit"><?=$label?></button>
        </div>
    </form>    
<?php } ?>