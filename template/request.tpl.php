
<?php 
    require_once(__DIR__ . '/../template/input.tpl.php');
?>

<?php function draw_request_form($userName, $userID, $serviceID) { ?>
    <h2>Hire <?=$userName?> and make your Request</h2>
    <form id="requestForm" action="/../action/actionCreateRequest.php" method="post">
        <?php draw_text_inputs(); ?>
        <input type="hidden" value="<?=$userID?>" name="userID">
        <input type="hidden" value="<?=$serviceID?>" name="serviceID">
    </form>

    <div>
        <button type="submit" class="green-button" form="requestForm">Hire</button>
    </div>
<?php } ?>