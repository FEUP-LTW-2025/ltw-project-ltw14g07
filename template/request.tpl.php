
<?php 
    require_once(__DIR__ . '/../template/input.tpl.php');
    require_once(__DIR__ . '/../template/comment.tpl.php');
?>

<?php function draw_request_page($request, $comments, $user) { ?>
    <?php
        draw_request($request);
        if ($request->status === 'pending') draw_decision_buttons($request);
        draw_edit_request($request);
        draw_delete_request($request->requestID);
        draw_request_chat($comments, $request->requestID, $user);
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
                <?php } 
            ?>
        </ul>
        <form action="/../action/actionCreateComment.php" method="post">
            <input type="text" name="message" placeholder="type your message">
            <input type="hidden" value=<?=$requestID?> name="requestID">
            <input type="hidden" value=<?=$user->userID?> name="userID">
            <button type="submit">Send</button>
        </form>
    </section>
<?php } ?>


<?php function draw_request_cards($requests, $status = null) { ?>
    <section class="card listing">
        <h1><?=$status?> Requests</h1>
        <?php
            if (empty($requests)) { ?>
                <h2> No <?=$status?> requests yet</h2>
            <?php }
        ?>
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
            <p><?=$request->description?></p>
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
    </form>

    <div>
        <button type="submit" class="green-button" form="requestForm"><?=$button?></button>
    </div>

<?php } ?>