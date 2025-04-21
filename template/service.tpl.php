<?php
    require_once(__DIR__ . '/../database/service.class.php');
?>


<?php function draw_service($service) { ?>
    <?php
        $hourlyRate = "$service->hourlyRate" . "$/h";
        $deliveryTime = "$service->deliveryTime" . " weeks";
    ?>
    <section>
        <aside>
            <img src="https://picsum.photos/id/237/200/300">
            <p><?=$service->userName?></p>
        </aside>
        <article>
            <h1 class="title"><?=$service->title?></h1>
            <p><?=$service->description?></p>

            <div>
                <section class="skills">
                    <article>
                        <h1>Fields:</h1>
                        <?php draw_tags($service->fields) ?>  
                    </article>

                    <article>
                        <h1>Languages:</h1>
                        <?php draw_tags($service->languages) ?>
                    </article>
                </section>

                <section class="pricing">
                    <article>
                        <h1>Hourly Rate:</h1>
                        <p><?=$hourlyRate?></p>
                    </article>

                    <article>
                        <h1>Delivery Time:</h1>
                        <p><?=$deliveryTime?></p>
                    </article>
                </section>
            </div>
        </article>
    </section>
<?php } ?>


<?php function draw_service_cards($services) { ?>
    <section class="card">
        <?php foreach($services as $service) { 
            draw_service_card($service);
        } ?>
    </section>
<?php } ?>


<?php function draw_service_card($service) { 
    $shortDescription = substr($service->description, 0, 250);
    $hourlyRate = "$service->hourlyRate" . "$/h";
    $deliveryTime = "$service->deliveryTime" . " weeks";

    if (strlen($service->description) > 250) {
        $shortDescription .= '...';
    }
    ?>
    
    <article class="service-card">
        <section>
            <img src="https://picsum.photos/id/237/200/300">
            <div>
                <h3 class="med-label"><?=$service->title?></h3>
                <p><?=$service->userName?></p>
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
        <input type="hidden" name="serviceID" value="<?=$service->serviceID?>">
    </article>
<?php } ?> 


<?php function draw_tags($tagList) { ?>
    <div class="tag-list">
        <?php foreach ($tagList as $tag) { ?>
            <span><?=$tag?></span>
        <?php } ?>
    </div>
<?php } ?> 

