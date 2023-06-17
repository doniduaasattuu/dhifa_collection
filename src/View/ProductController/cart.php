<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"><!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <title><?= $model["title"] ?></title>
</head>

<style>
    #shopping_summary {
        width: 23rem;
    }

    @media (max-width: 576px) {
        #shopping_summary {
            width: 100%;
        }

        .clean_basket {
            margin-bottom: 1rem;
            width: 100%;
        }

        .checkout {
            width: 100%;
        }
    }
</style>

<body>

    <?= $model["navbar"] ?>

    <div class="container py-5">
        <div class="m-1 d-flex justify-content-between align-items-baseline">
            <div>
                <h2><?php
                    $firstname = explode(" ", $_SESSION["fullname"]);
                    echo $firstname[0];
                    ?>'s Cart</h2>
            </div>
            <div>
                <span class="invoice"><?= $model["invoice"] ?></span>
            </div>
        </div>

        <table class="table my-3">
            <thead>
                <tr>
                    <th class="count-product" scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Subtotal</th>
                </tr>
            </thead>
            <tbody>

                <?php
                $count = 1;
                foreach ($model["cart"] as $cart) {
                ?>
                    <tr>
                        <th class="count-product align-middle" scope="row"><?php echo $count ?></th>
                        <td class="align-middle"><?php echo $cart["name"]; ?></td>
                        <td class="align-middle"><span class="price"><?php echo $cart["price"]; ?></span>K</td>
                        <td class="align-middle">
                            <button product_id="<?php echo $cart["product_id"] ?>" class="button_decrement btn btn-outline-primary btn-sm me-1">-</button>
                            <span class="text-center align-middle" style="width: 25px;" class="d-sm-inline-block"><?php echo $cart["qty"]; ?></span>
                            <button product_id="<?php echo $cart["product_id"] ?>" class="button_increment btn btn-outline-primary btn-sm ms-1">+</button>
                        <td class="align-middle"><span class="amount"><?php echo $cart["amount"]; ?></span>K</td>
                    </tr>
                <?php
                    $count++;
                }
                ?>

            </tbody>
        </table>

        <div class="row mt-4">
            <div class="col-sm">
                <button id="clean_basket" class="clean_basket mb-sm-0 btn btn-outline-danger">Delete basket</button>
            </div>
            <div class="col-sm">
                <div class="ms-auto card" id="shopping_summary">
                    <div class=" card-body">

                        <h5 class="fw-bold card-title mb-0">Cart details</h5>
                        <!-- <div class="d-flex justify-content-between align-items-baseline">
                            <button class="btn btn-primary">Verified</button>
                        </div> -->
                        <hr>

                        <!-- TOTAL AMOUNT {PRICE} -->
                        <p class="card-text">
                        <h6 class="fw-bold">Total price (<?= $count - 1 ?> items)</h6>
                        IDR <span class="total_amount">0</span>K</p>
                        <hr>

                        <!-- SHIPPING ADDRESS -->
                        <p class="card-text">
                        <h6 class="fw-bold">Shipping address</h6>
                        <span class="shipping_address text-secondary"><?= $_SESSION["address"] ?></span></p>

                        <!-- SHIPPING PRICE -->
                        <h6 class="fw-bold">Shipping price</h6>
                        IDR <span class="shipping_price">50</span>K</p>
                        <hr>

                        <!-- TOTAL PAYMENT -->
                        <p class="card-text">
                        <h6 class="fw-bold">Total payment</h6>
                        IDR <span class="total_payment">500</span>K</p>
                        <hr>

                        <div class="d-sm-flex d-block justify-content-between">
                            <select class="payment_method me-sm-4 form-select" aria-label="Default select example">
                                <option selected>Payment method</option>
                                <option value="ATM Transfer">ATM Transfer</option>
                                <option value="BRIVA">BRIVA</option>
                            </select>
                            <!-- <a href="#" class="checkout float-end mt-sm-0 mt-3 btn btn-primary">Checkout</a> -->
                            <button class="checkout float-end mt-sm-0 mt-3 btn btn-primary">Checkout</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    </div>

    <!-- FOOTER -->
    <?php
    echo $model["footer"];
    ?>

    <script>
        const button_decrement = document.getElementsByClassName("button_decrement");
        const button_increment = document.getElementsByClassName("button_increment");

        const price = document.getElementsByClassName("price");
        const amount = document.getElementsByClassName("amount");

        const total_amount = document.getElementsByClassName("total_amount")[0];
        const total_payment = document.getElementsByClassName("total_payment")[0];
        const shipping_price = document.getElementsByClassName("shipping_price")[0];

        const invoice = document.getElementsByClassName("invoice")[0];

        const checkout = document.getElementsByClassName("checkout")[0];
        const payment_method = document.getElementsByClassName("payment_method")[0];


        // PENGURANGAN KUANTITI
        for (let i = 0; i < button_decrement.length; i++) {
            button_decrement[i].onclick = () => {

                if (Number(button_decrement[i].nextElementSibling.textContent) > 1) {

                    let text_content = Number(button_decrement[i].nextElementSibling.textContent) - 1;
                    button_decrement[i].nextElementSibling.textContent = text_content;

                    let amount_update = Number(button_decrement[i].nextElementSibling.textContent) * Number(price[i].textContent)
                    amount[i].textContent = amount_update;

                    sum_total_amount();
                    sum_total_payment();

                    // =======================================
                    // UPDATE KUANTITI KE DATABASE MELALUI API
                    // =======================================
                    update_cart_quantity(button_decrement[i], "decrement", amount_update);

                }
            }
        }

        // PENAMBAHAN KUANTITI
        for (let i = 0; i < button_increment.length; i++) {

            button_increment[i].onclick = () => {

                let text_content = Number(button_increment[i].previousElementSibling.textContent) + 1;
                button_increment[i].previousElementSibling.textContent = text_content;

                let amount_update = Number(button_increment[i].previousElementSibling.textContent) * Number(price[i].textContent)
                amount[i].textContent = amount_update;

                sum_total_amount();
                sum_total_payment();

                // =======================================
                // UPDATE KUANTITI KE DATABASE MELALUI API
                // =======================================
                update_cart_quantity(button_increment[i], "increment", amount_update);

            }
        }


        // FUNCTION UPDATE KUANTITI KE DATABASE 
        function update_cart_quantity(button, method, amount) {
            const update_cart_quantity_api = new XMLHttpRequest();

            update_cart_quantity_api.open("POST", "update_cart_quantity");

            let product_id_to_update = button.getAttribute("product_id");

            update_cart_quantity_api.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            update_cart_quantity_api.send(`product_id=${product_id_to_update}&function=${method}&amount=${amount}`);
        }

        // SUM AMOUNT AS TOTAL AMOUNT
        function sum_total_amount() {

            let temp = 0;
            for (let i = 0; i < amount.length; i++) {

                let amount_temp = Number(amount[i].textContent);
                temp = temp + amount_temp;

                total_amount.textContent = temp;

            }
        }

        // SUM TOTAL PAYMMENT
        function sum_total_payment() {

            xhr = new XMLHttpRequest()
            xhr.open("POST", "total_payment");

            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.send(`total_payment=${Number(total_amount.textContent) + Number(shipping_price.textContent)}&invoice=${invoice.textContent}`)

            total_payment.textContent = Number(total_amount.textContent) + Number(shipping_price.textContent);

        };

        // WINDOW ON LOAD
        window.onload = () => {

            sum_total_amount();
            sum_total_payment();

            let MyArray = ["Doni", "Darmawan"];
            let NewArray = new Array("Doni", "Darmawan");

        }

        // =================================
        // CLEAN BASKET FUNCTION 
        // =================================
        const clean_basket_button = document.getElementById("clean_basket");
        clean_basket_button.onclick = () => {

            const sure_delete_basket = confirm("Do you really want to delete ?")

            if (sure_delete_basket == true) {
                const clean_basket_api = new XMLHttpRequest();

                clean_basket_api.open("POST", "clean_basket");

                clean_basket_api.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                clean_basket_api.send();

                alert("Basket was cleaned!")
                location.reload();
            }
        }


        // ====================
        // CHECKOUT
        // ====================

        checkout.onclick = () => {
            if (payment_method.value == "Payment method") {
                alert("Select payment method first!")
            } else {

                if (payment_method.value == "ATM Transfer") {

                    window.location = "update_status"

                } else if (payment_method.value == "BRIVA") {

                    alert("Sorry, this service is temporarily unavailable");

                } else {
                    console.info("Tidak diketahui")
                }
            }
        }
    </script>
</body>

</html>