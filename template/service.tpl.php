<?php
    require_once(__DIR__ . '/../database/service.class.php');
    require_once(__DIR__ . '/../template/request.tpl.php');
?>

<?php function draw_service_page($service, $pendingRequests, $acceptedRequests, $request, Session $session) { 
    draw_service($service);
    if ($session->getUserID() === $service->userID) {
        draw_user_options($service->serviceID);
        draw_request_cards($pendingRequests, 'Pending');
        draw_request_cards($acceptedRequests, 'Accepted');
    } else {
        draw_request_form($service->userName, $service->serviceID, $request, $session);
    }
 } 
 ?>


<?php function draw_service($service) { ?>
    <?php
        $hourlyRate = "$service->hourlyRate" . "$/h";
        $deliveryTime = "$service->deliveryTime" . " weeks";
        $path = __DIR__ . "/../images/profile/$service->userID.jpg";
        $src = file_exists($path) ? "/../images/profile/$service->userID.jpg" : "/../images/profile/default.jpg";
    ?>
    <section class='service'>
        <aside>
            <a href="/pages/profile.php?id=<?=$service->userID?>">
                <img src=<?=$src?>>
            </a>
            <p><?=htmlspecialchars($service->userName)?></p>
        </aside>
        <article>
            <h1><?=htmlspecialchars($service->title)?></h1>
            <p><?=htmlspecialchars($service->description)?></p>

            <div class="spaced">
                <section class="skills">
                    <article>
                        <h2>Fields:</h2>
                        <?php draw_tags($service->fields) ?>  
                    </article>

                    <article>
                        <h2>Languages:</h2>
                        <?php draw_tags($service->languages) ?>
                    </article>
                </section>

                <section class="pricing">
                    <article>
                        <h2>Hourly Rate:</h2>
                        <p><?=$hourlyRate?></p>
                    </article>

                    <article>
                        <h2>Delivery Time:</h2>
                        <p><?=$deliveryTime?></p>
                    </article>
                </section>
            </div>
            <?php
            draw_service_img($service->serviceID);
            ?>
        </article>
    </section>
<?php } ?>


<?php function draw_service_img($serviceID)  { 
        $path = __DIR__ . "/../images/service/$serviceID.jpg";
        $src = "/../images/service/$serviceID.jpg";
        if (file_exists($path)) { ?>
            <h2>Attached Image:</h2>
            <img src = <?=$src?> >
    <?php }
 } ?>


<?php function draw_user_options($serviceID) { ?>
    <div class="right">
        <?php
        draw_edit_service($serviceID);
        draw_delete_service($serviceID);
        ?>
    </div>
<?php } ?>

<?php function draw_edit_service($serviceID) { ?>
    <a class="green-button" href="/../pages/createService.php?serviceID=<?=$serviceID?>">Edit</a>
<?php } ?>


<?php function draw_delete_service($serviceID) { ?>
    <form action="/../action/actionDeleteService.php" method="post">
        <input type="hidden" name="serviceID" value=<?=$serviceID?>>
        <button class="red-button" type="submit">Delete</button>
    </form>
<?php } ?>


<?php function draw_service_cards($services, $userID = null) { ?>
    <section class="card listing" data-id="<?=$userID?>">
        <h1>Services</h1>
        <?php if (!empty($services)): ?>
            <ul>
                <?php foreach($services as $service): 
                    draw_service_card($service);
                endforeach; ?>
            </ul>
            <div class="center">
                <button id="loadMore" class="green-button">Load More</button>
            </div>
        <?php else: ?>
            <h2>No Services yet</h2>
        <?php endif; ?>
        
    </section>
<?php } ?>


<?php function draw_service_card($service) { 
    $shortDescription = substr($service->description, 0, 250);
    $hourlyRate = "$service->hourlyRate" . "$/h";
    $deliveryTime = "$service->deliveryTime" . " weeks";
    $path = __DIR__ . "/../images/profile/$service->userID.jpg";
    $src = file_exists($path) ? "/../images/profile/$service->userID.jpg" : "/../images/profile/default.jpg";

    if (strlen($service->description) > 250) {
        $shortDescription .= '...';           
    }

    ?>
    <li id="service-card" class="info-card" data-id="<?=$service->serviceID?>">
        <section>
            <a href="/pages/profile.php?id=<?=$service->userID?>">
                <img src=<?=$src?>>
            </a>
            <div>
                <h3><?=htmlspecialchars($service->title)?></h3>
                <p><?=htmlspecialchars($service->userName)?></p>
            </div>
        </section>
        <p><?=$shortDescription?></p>
        <section>
            <div>
                <h4>Hourly Rate</h4>
                <p><?=$hourlyRate?></p>
            </div>

            <div>
                <h4>Delivery Time</h4>
                <p><?=$deliveryTime?></p>
            </div>

            <article>
                <?php 
                draw_tags($service->languages);
                draw_tags($service->fields);
                ?>
            </article>
        </section>
    </li>
<?php } ?> 


<?php function draw_tags($tagList) { ?>
    <div class="wrap-list">
        <?php foreach ($tagList as $tag) { ?>
            <span><?=$tag?></span>
        <?php } ?>
    </div>
<?php } ?> 

