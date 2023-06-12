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
    }
</style>

<body>

    <?= $model["navbar"] ?>

    <div class="container vh-100 py-5">
        <div class="container-fluid d-flex justify-content-between">
            <h1><?php
                $firstname = explode(" ", $_SESSION["fullname"]);
                echo $firstname[0];
                ?>'s Cart</h1>
        </div>

        <table class="table my-3">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Amount</th>
                </tr>
            </thead>
            <tbody>

                <?php
                $count = 1;
                foreach ($model["cart"] as $cart) {
                ?>
                    <tr>
                        <th class="align-middle" scope="row"><?php echo $count ?></th>
                        <td class="align-middle"><?php echo $cart["name"]; ?></td>
                        <td class="price align-middle"><?php echo $cart["price"]; ?>K</td>
                        <td class="align-middle">
                            <button product_id="<?php echo $cart["product_id"] ?>" class="button_decrement btn btn-outline-primary btn-sm me-1">-</button>
                            <span class="text-center align-middle" style="width: 25px;" class="d-sm-inline-block"><?php echo $cart["qty"]; ?></span>
                            <button product_id="<?php echo $cart["product_id"] ?>" class="button_increment btn btn-outline-primary btn-sm ms-1">+</button>
                        <td class="amount align-middle"><?php echo $cart["amount"]; ?>K</td>
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
                        <h5 class="fw-bold mb-3 card-title">Shopping summary</h5>
                        <p class="pt-3 border-top card-text"><span class=" fw-bold">Total amount</span> : IDR <span class="total_amount">0</span>K</p>
                        <hr>
                        <p class="card-text"><span class="fw-bold">Shipping address</span> : <?= $_SESSION["address"] ?></p>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <a href="#" class="btn btn-primary">Checkout</a>
                            <select class="ms-4 form-select" aria-label="Default select example">
                                <option selected>Payment method</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        const button_decrement = document.getElementsByClassName("button_decrement");
        const button_increment = document.getElementsByClassName("button_increment");

        const price = document.getElementsByClassName("price");
        const amount = document.getElementsByClassName("amount");

        const total_amount = document.getElementsByClassName("total_amount")[0];

        // PENGURANGAN KUANTITI
        for (let i = 0; i < button_decrement.length; i++) {
            button_decrement[i].onclick = () => {

                if (Number(button_decrement[i].nextElementSibling.textContent) > 1) {

                    let text_content = Number(button_decrement[i].nextElementSibling.textContent) - 1;
                    button_decrement[i].nextElementSibling.textContent = text_content;

                    let amount_update = Number(button_decrement[i].nextElementSibling.textContent) * Number(price[i].textContent.replace("K", ""))
                    amount[i].textContent = amount_update + "K";

                    sum_total_amount();

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

                let amount_update = Number(button_increment[i].previousElementSibling.textContent) * Number(price[i].textContent.replace("K", ""))
                amount[i].textContent = amount_update + "K";

                sum_total_amount();

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

                let amount_temp = Number(amount[i].textContent.replace("K", ""));
                temp = temp + amount_temp;
                total_amount.textContent = temp;

            }
        }

        // WINDOW ON LOAD
        window.onload = () => {
            sum_total_amount();
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
    </script>
</body>

</html>