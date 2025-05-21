<?php
require_once(__DIR__ . '/../template/editor_input.tpl.php');

function draw_profile_editor($user) { ?>
    <form id="serviceForm" action="/../action/actionEditProfile.php" method="post" enctype="multipart/form-data">
        <?php draw_editor_inputs($user); ?>
        <?php draw_pubs_button(); ?>
    </form>
<?php } ?>