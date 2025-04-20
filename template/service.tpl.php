<?php
    require_once(__DIR__ . '/../database/service.class.php');

?>


<?php function draw_service() { ?>
    <section>
        <aside>
            <img src="https://picsum.photos/id/237/200/300">
            <p>John Doe</p>
        </aside>
        <article>
            <h1 class="title">I will do modern mobile app ui ux design or website ui ux design</h1>
            <p>As a UI UX designer, I put much value on trustful, transparent, long-term relationships. That's why I'm very accurate in performing a professional approach. Your privacy, terms, and deadlines will always be respected. 
                All I need to start is your specifications, a description of a problem you face, or just an initial idea of the future design product. But in case you are not sure at all - no problem. We will work out the product's vision together, and I will provide you with fresh and unique ideas and efficient methods to create something outstanding and productive. I will manage your design project from start to final result. Feel free to contact me to discuss the details.</p>

            <div>
                <section class="skills">
                    <article>
                        <h1>Fields:</h1>
                        <div class="tag-list">
                            <span>UI/UX</span>
                        </div>
                    </article>

                    <article>
                        <h1>Languages:</h1>
                        <div class="tag-list">
                            <span>PHP</span>
                            <span>HTML</span>
                            <span>CSS</span>
                            <span>JavaScript</span>
                        </div>
                    </article>
                </section>

                <section class="pricing">
                    <article>
                        <h1>Hourly Rate:</h1>
                        <p>15/h</p>
                    </article>

                    <article>
                        <h1>Delivery Time:</h1>
                        <p>6 weeks</p>
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
    </article>
<?php } ?> 


<?php function draw_tags($tagList) { ?>
    <div class="tag-list">
        <?php foreach ($tagList as $tag) { ?>
            <span><?=$tag?></span>
        <?php } ?>
    </div>
<?php } ?> 










<?php function draw_request_form() { ?>
    <form id="requestForm">
        <h2>Hire John Doe and make your Request</h2>

        <section class="labeled-input">
            <h3 class="med-label">Title</h3>
            <input class="card" type="textarea" placeholder="Write your Title..">
        </section>

        <section class="labeled-input">
            <h3 class="med-label">Description</h3>
            <textarea class="card" name="description" rows="7" placeholder="Write your Description..."></textarea>
        </section>
    </form>

    <div>
        <button class="green-button">Hire</button>
    </div>
<?php } ?>