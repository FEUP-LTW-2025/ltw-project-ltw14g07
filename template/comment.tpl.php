

<?php function draw_comments($comments) { 
    foreach($comments as $comment) {
        draw_comment($comment);
    }
} ?>

<?php function draw_comment($comment) { ?>
    <li class="info-card">
        <p><?=htmlspecialchars($comment->userName)?></p>
        <p><?=htmlspecialchars($comment->text)?></p>
     </li>
 <?php } ?>


