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

        .cancel_order {
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

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="bg-light modal-header">
                    <h1 class=" modal-title fs-5" id="exampleModalLabel">Successfully uploaded! ✅</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Your payment receipt is successfully uploaded,
                    wait until it arrives at your home, thanks for your payment.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div style="max-width: 400px" class="container my-5 ">
        <div class=" border border-1 rounded">
            <div class="p-3">
                <div>
                    <div class="d-flex justify-content-between">
                        <p id="invoice"><?= $model["invoice"] ?></p>
                        <?php
                        if (isset($model["verified"])) {
                            $status = <<<STATUS
                                <p class="bg-success px-2 text-light rounded">Verified</p>
                                STATUS;
                            echo $status;
                        }
                        ?>
                    </div>
                    <p>Transfer to the account number below</p>
                    <span class="fw-bold"><img style="width: 25px; margin-right: 0.25rem" src="img/BRI.png" alt="BRI">4440004051502</span>
                    <p>Total payment
                        <span><?= $model["total_payment"][0]["total_payment"] ?>K</span>
                    </p>

                </div>
                <form method="POST" action="upload_resi" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="mb-2" for="payment_receipt">Upload payment receipt</label>
                        <input type="file" class="d-inline-block form-control-file" name="payment_receipt" id="payment_receipt" accept="image/*">
                    </div>
                    <input disabled id="upload_button" class="w-100 my-3 btn btn-primary" type="submit" value="Upload">
                </form>
                <button id="cancel_order" class=" w-100 btn btn-outline-danger">Cancel order</button>
            </div>
        </div>
    </div>


    <?php

    if (isset($model["upload_success"])) {
        $modal = <<<MODAL
                <script>
                    let myModal = new bootstrap.Modal(document.getElementById('exampleModal'), {});
                    myModal.show();
                </script>
                MODAL;
        echo $modal;
    }

    if (isset($model["disabled_cancel"])) {
        $modal = <<<MODAL
                <script>
                    document.getElementById("cancel_order").setAttribute("disabled", "");
                </script>
                MODAL;
        echo $modal;
    }

    ?>




    <script>
        // =================================
        // CLEAN BASKET FUNCTION 
        // =================================

        const invoice = document.getElementById("invoice").textContent;

        const cancel_order_button = document.getElementById("cancel_order");
        cancel_order_button.onclick = () => {

            const sure_cancel_order = confirm("Do you really want to canel this order ?")

            if (sure_cancel_order == true) {

                window.location = "cancel_order";

            }
        }

        // =================================
        // ENABLE UPLOAD BUTTON
        // =================================
        let upload_button = document.getElementById("upload_button");
        let input_file = document.getElementById("payment_receipt");

        input_file.onchange = () => {
            upload_button.removeAttribute("disabled");
        }
    </script>
</body>

</html>