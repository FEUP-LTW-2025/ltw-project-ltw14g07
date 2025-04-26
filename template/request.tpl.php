
<?php 
    require_once(__DIR__ . '/../template/input.tpl.php');
?>

<?php function draw_request_page($request, $comments) { ?>
    <?php
        draw_request($request);
        draw_decision_buttons();
        draw_request_chat($comments);
        
    ?>
<?php } ?>

<?php function draw_request_chat($comments) { ?>
    <section class="card listing">
        <h1>Chat</h1>
        <ul>
            <?php
                foreach($comments as $comment) {
                    draw_comment($comment);
                }
            ?>
        </ul>
    </section>
<?php } ?>

<?php function draw_comment($comment) { ?>
    <li class="info-card">
        <p><?=$comment->userName?></p>
        <p><?=$comment->text?></p>
     </li>
 <?php } ?>


<?php function draw_decision_buttons() { ?>
    <button>Accept</button>
    <button>Deny</button>
<?php } ?>


<?php function draw_request($request) { ?>
    <h1><?=$request->title?></h1>
    <p><?=$request->notes?></p>
<?php } ?>



<?php function draw_request_cards($requests) { ?>
    <section class="card listing">
        <h1>Requests</h1>
        <ul>
            <?php foreach($requests as $request) { 
                draw_request_card($request);
            } ?>
        </ul>
    </section>
 <?php } ?>


<?php function draw_request_card($request) { ?>
    <li class="info-card">
        <a href="request.php?id=<?=$request->requestID?>">
            <h2><?=$request->title?></h2>
            <p><?=$request->notes?></p>
        </a>
    </li>
 <?php } ?>


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