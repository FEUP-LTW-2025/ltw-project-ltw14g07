<?php function draw_payment_form() { ?>
    <form>
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

        <section class="spaced">
            <div>
                <h3>Card Type* :
                    <select name="card_type" id="card_type" required="">
                    <option value="">---Select a Card type---</option>
                    <option value="visa">Visa</option>
                    <option value="rupy">Rupay</option>
                    <option value="master card">Master Card</option>        
                    <option value="master card">American Express</option>        
                    </select>    
                </h3>
                <h3>
                    Card Number* : <input type="number" name="card Number" id="Card Number" required="">
                </h3>            
            </div>

            <div>
                <h3>
                    Expiration Date* : <input type="date" name="exp_date" id="exp_date" required="">
                </h3>
                <h3>
                    CVV* : <input type="password" name="CVV" id="CVV" required="">
                </h3>
            </div>
        </section>

        <div class="right">
            <input class="green-button" type="submit" name="" id="" value="Confirm" />
        </div>

    </form>
<?php } ?> 