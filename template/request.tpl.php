
<?php 
    require_once(__DIR__ . '/../template/input.tpl.php');
    require_once(__DIR__ . '/../template/comment.tpl.php');
    require_once(__DIR__ . '/../utils/session.php');
?>

<?php function draw_request_page($request, $comments, Session $session) { ?>
    <?php
        draw_request($request);
        if ($session->getUserID() === $request->userID) {    //is creator
            draw_creator_options($request);
        }
        else {   //is freelancer
            if ($request->status === 'pending') draw_decision_buttons($request, $session);
            if ($request->status === 'accepted') draw_done_button($request, $session);
        }
        draw_request_chat($comments, $request->requestID, $session);
    ?>
<?php } ?>

<?php function draw_request($request) { ?>
    <h1><?=htmlspecialchars($request->title)?></h1>
    <p><?=htmlspecialchars($request->description)?></p>
    <p>Status: <?=$request->status?></p>
<?php } ?>


<?php function draw_creator_options($request) { ?>
    <div class="wrap-list right">
        <?php
        draw_edit_request($request);
        draw_delete_request($request->requestID);
        ?>
    </div>
<?php } ?>


<?php function draw_delete_request($requestID) { ?>
    <form action="/../action/actionDeleteRequest.php" method="post">
        <input type="hidden" name="requestID" value=<?=$requestID?>>
        <button type="submit" class="red-button">Delete</button>
    </form>
<?php } ?>

<?php function draw_edit_request($request) { ?>
    <a class="green-button" href="/../pages/service.php?serviceID=<?=$request->serviceID?>&requestID=<?=$request->requestID?>">Edit</a>
<?php } ?>



<?php function draw_decision_buttons($request, Session $session) {  ?>
    <form class="wrap-list right" method="post" action="/../action/actionCreateRequest.php">
        <input type="hidden" name="requestID" value="<?=$request->requestID?>">
        <input type="hidden" name="serviceID" value="<?=$request->serviceID?>">
        <input type="hidden" name="csrf" value=<?=$session->getCsrf()?>>
        <button class="green-button" type="submit" name="decision" value="accepted">Accept</button>
        <button class="red-button" type="submit" name="decision" value="denied">Deny</button>
    </form>
<?php } ?>

<?php function draw_done_button($request, Session $session) {  ?>
    <form class="wrap-list right" method="post" action="/../action/actionCreateRequest.php">
        <input type="hidden" name="requestID" value="<?=$request->requestID?>">
        <input type="hidden" name="serviceID" value="<?=$request->serviceID?>">
        <input type="hidden" name="csrf" value=<?=$session->getCsrf()?>>
        <button class="green-button" type="submit" name="decision" value="done">Done</button>
    </form>
<?php } ?>


<?php function draw_request_chat($comments, $requestID, Session $session) { ?>
    <section class="card listing">
        <h1>Chat</h1>
        <ul class="overflow">
            <?php if (empty($comments)): ?>
            <h3>No comments yet</h3>
        <?php else: ?>
            <?php draw_comments($comments) ?>
        <?php endif; ?>
        </ul>

        <form>
            <input class="info-card" type="text" name="message" placeholder="type your message">
            <input type="hidden" value=<?=$requestID?> name="requestID">
            <input type="hidden" value=<?=$session->getCsrf()?> name="csrf">
            <button class="green-button" type="submit">Send</button>
        </form>
    </section>
<?php } ?>


<?php function draw_request_cards($requests, $status = null) { ?>
    <section class="card listing">
        <h1><?=$status?> Requests</h1>
        <?php if (empty($requests)): ?>
            <h2> No <?=$status?> requests yet</h2>
        <?php else: ?>
            <ul class="pin-board overflow">
                <?php foreach($requests as $request)
                    draw_request_card($request);
                ?>
            </ul>
        <?php endif; ?>
    </section>
 <?php } ?>


<?php function draw_request_card($request) { ?>
    <?php
        $shortDescription = substr($request->description, 0, 250);
        if (strlen($request->description) > 250) {
            $shortDescription .= '...';
        }
    ?>
    <li class="info-card">
        <a href="request.php?id=<?=$request->requestID?>">
            <h2><?=htmlspecialchars($request->title)?></h2>
            <p><?=htmlspecialchars($shortDescription)?></p>
        </a>
    </li>
 <?php } ?>




<?php function draw_request_form($userName, $serviceID, $request, Session $session) { ?>
    <?php
    $requestID = "";
    $label = "Hire " . $userName . " and make your Request";
    $button = "Hire";
    if(isset($request)) {
        $requestID = $request->requestID;
        $label = "Update your Request";
        $button = "Save";
    }
    ?>
    <h2><?=$label?></h2>
    <form id="requestForm" action="/../action/actionCreateRequest.php" method="post">
        <?php draw_text_inputs($request); ?>
        <input type="hidden" value="<?=$serviceID?>" name="serviceID">
        <input type="hidden" value="<?=$requestID?>" name="requestID">
        <input type="hidden" value=<?=$session->getCsrf()?> name="csrf">
    </form>

    <div class="right">
        <button type="submit" class="green-button" form="requestForm"><?=$button?></button>
    </div>

<?php } ?>