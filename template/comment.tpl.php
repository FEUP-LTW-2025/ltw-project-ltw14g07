

<?php function draw_comments($comments) { 
    foreach($comments as $comment) {
        draw_comment($comment);
    }
} ?>

<?php function draw_comment($comment) { ?>
    <li class="info-card">
        <p><?=$comment->userName?></p>
        <p><?=$comment->text?></p>
     </li>
 <?php } ?>


