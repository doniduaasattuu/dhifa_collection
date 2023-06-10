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
        width: 25rem;
    }

    @media (max-width: 576px) {
        #shopping_summary {
            width: 100%;
        }
    }
</style>

<body style="scrollbar-width: none;">

    <?= $model["navbar"] ?>

    <div class="container vh-100 py-5">
        <h1><?= $model["content"] ?></h1>

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
                            <button class="button_decrement btn btn-outline-primary btn-sm me-1">-</button>
                            <span class="text-center align-middle" style="width: 25px;" class="d-sm-inline-block"><?php echo $cart["qty"]; ?></span>
                            <button class="button_increment btn btn-outline-primary btn-sm ms-1">+</button>
                        <td class="amount align-middle"><?php echo $cart["amount"]; ?>K</td>
                    </tr>
                <?php
                    $count++;
                }
                ?>

            </tbody>
        </table>

        <div class="ms-auto mt-4 card" id="shopping_summary">
            <div class=" card-body">
                <h5 class="card-title">Shopping summary</h5>
                <p class="pt-3 border-top card-text"><span class=" fw-medium">Total amount</span> : IDR <span class="total_amount">900</span>K</p>
                <hr>
                <p class="card-text"><span class="fw-medium">Shipping address</span> : Lorem, ipsum dolor sit amet consectetur adipisicing elit. Iste error ducimus inventore. Iusto, explicabo voluptatibus?</p>
                <hr>
                <a href="#" class="btn btn-primary">Checkout</a>
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
            }
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
    </script>
</body>

</html>