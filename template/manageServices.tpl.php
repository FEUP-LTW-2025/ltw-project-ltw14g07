<?php 
    require_once(__DIR__ . '/../template/service.tpl.php');
    require_once(__DIR__ . '/../utils/session.php');

?>

<?php function draw_manageServices_page($services, int $userID) { ?>
    <h1>Manage your published Services, create, edit or delete them</h1>
    <?php 
    draw_service_cards($services, $userID); 
    draw_createService_button()
    ?>  

<?php } ?>


<?php function draw_createService_button() { ?>
    <a class="green-button" href="/pages/createService.php"> Create Service</a>
<?php } ?>
