
<?php 
    require_once(__DIR__ . '/../template/input.tpl.php');
    require_once(__DIR__ . '/../template/comment.tpl.php');
?>

<?php function draw_request_page($request, $comments, $user) { ?>
    <?php
        draw_request($request);
        draw_decision_buttons();
        draw_request_chat($comments, $request->requestID, $user);
        
    ?>
<?php } ?>

<?php function draw_request_chat($comments, $requestID, $user) { ?>
    <section class="card listing">
        <h1>Chat</h1>
        <ul>
            <?php
                if (!empty($comments)) {
                    draw_comments($comments);
                }
                else { ?>
                    <h3>No comments yet</h3>
                <?php } ?>
        </ul>
        <form action="/../action/actionCreateComment.php" method="post">
            <input type="text" name="message" placeholder="type your message">
            <input type="hidden" value=<?=$requestID?> name="requestID">
            <input type="hidden" value=<?=$user->userID?> name="userID">
            <button type="submit">Send</button>
        </form>
    </section>
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