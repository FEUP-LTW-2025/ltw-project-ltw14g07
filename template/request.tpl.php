
<?php 
    require_once(__DIR__ . '/../template/input.tpl.php');
    require_once(__DIR__ . '/../template/comment.tpl.php');
    session_start();
?>

<?php function draw_request_page($request, $comments) { ?>
    <?php
        draw_request($request);
        if ($_SESSION['userID'] === $request->userID) {    //is creator
            draw_edit_request($request);
            draw_delete_request($request->requestID);
        }
        else {   //is freelancer
            if ($request->status === 'pending') draw_decision_buttons($request);
        }
        draw_request_chat($comments, $request->requestID);
    ?>
<?php } ?>

<?php function draw_request($request) { ?>
    <h1><?=$request->title?></h1>
    <p><?=$request->description?></p>
    <p>Status: <?=$request->status?></p>
<?php } ?>


<?php function draw_delete_request($requestID) { ?>
    <form action="/../action/actionDeleteRequest.php" method="post">
        <input type="hidden" name="requestID" value=<?=$requestID?>>
        <button type="submit">Delete</button>
    </form>
<?php } ?>


<?php function draw_decision_buttons($request) {  ?>
    <form method="post" action="/../action/actionCreateRequest.php">
        <input type="hidden" name="requestID" value="<?=$request->requestID?>">
        <input type="hidden" name="serviceID" value="<?=$request->serviceID?>">
        <button type="submit" name="decision" value="accepted">Accept</button>
        <button type="submit" name="decision" value="denied">Deny</button>
    </form>
<?php } ?>

<?php function draw_edit_request($request) { ?>
    <a href="/../pages/service.php?serviceID=<?=$request->serviceID?>&requestID=<?=$request->requestID?>">Edit Request</a>
<?php } ?>


<?php function draw_request_chat($comments, $requestID) { ?>
    <section class="card listing">
        <h1>Chat</h1>
        <?php if (empty($comments)): ?>
            <h3>No comments yet</h3>
        <?php else: ?>
            <ul class="overflow">
                <?php draw_comments($comments) ?>
            </ul>
        <?php endif; ?>

        <form action="/../action/actionCreateComment.php" method="post">
            <input type="text" name="message" placeholder="type your message">
            <input type="hidden" value=<?=$requestID?> name="requestID">
            <input type="hidden" value=<?=$_SESSION['csrf']?> name="csrf">
            <button type="submit">Send</button>
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
            <h2><?=$request->title?></h2>
            <p><?=$shortDescription?></p>
        </a>
    </li>
 <?php } ?>




<?php function draw_request_form($userName, $serviceID, $request) { ?>
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
        <input type="hidden" value=<?=$_SESSION['csrf']?> name="csrf">
    </form>

    <div>
        <button type="submit" class="green-button" form="requestForm"><?=$button?></button>
    </div>

<?php } ?>