<?php
    require_once(__DIR__ . '/../utils/session.php');
?>

<?php function draw_payment_form($requestID, $serviceID, Session $session) { ?>
    <form action="/../action/actionCreatePayment.php" method="post">
        <article>
            <h1>Payment Form</h1>
            <h3>The transaction will only be realized when the request is done by the freelancer</h3>
            <h4>(Required fields are *)</h4>
        </article>

        <section>
            <h2>Contact information</h2>
            <h3>Name* : 
                <input type="text" name="name" required="">
            </h3>
            <h3>Address: <textarea name="address" id="address" rows="6" cols="80"></textarea></h3>
        </section>

        <hr>

        <h2>Payment Information</h2>

        <section class="spaced wrap-list">
            <div>
                <h3>Card Type* :
                    <select name="card_type" id="card_type" required="">
                    <option value="visa">Visa</option>
                    <option value="rupay">Rupay</option>
                    <option value="mastercard">Master Card</option>     
                    <option value="paypal">Paypal</option>        
                    </select>    
                </h3>
                <h3>
                    Card Number* : <input type="number" name="card Number" id="Card Number" min="100000000" required="">
                </h3>            
            </div>

            <div>
                <h3>
                    Expiration Date* : <input type="date" name="exp_date" id="exp_date" required="">
                </h3>
                <h3>
                    CVV* : <input type="password" name="CVV" id="CVV" minlength="3" maxlength="4" required="">
                </h3>
            </div>
        </section>

        <input type="hidden" name="requestID" value="<?=$requestID?>">
        <input type="hidden" name="serviceID" value="<?=$serviceID?>">
        <input type="hidden" name="csrf" value="<?=$session->getCsrf()?>">

        <div class="right">
            <button class="green-button" type="submit">Confirm</button> 
        </div>

    </form>
<?php } ?> 