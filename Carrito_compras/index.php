<?php
session_start();
$connect = mysqli_connect("localhost", "root", "", "carrito_ventas");

if (isset($_POST["add_to_cart"])) {
    if (isset($_SESSION["shopping_cart"])) {
        $item_array_id = array_column($_SESSION["shopping_cart"], "item_id");
        if (!in_array($_GET["id"], $item_array_id)) {
            $count = count($_SESSION["shopping_cart"]);
            $item_array = array(
                'item_id'           =>  $_GET["id"],
                'item_name'         =>  $_POST["hidden_name"],
                'item_price'        =>  $_POST["hidden_price"],
                'item_quantity'     =>  $_POST["quantity"]
            );
            $_SESSION["shopping_cart"][$count] = $item_array;
        } else {
            echo '<script>alert("Producto ya fue agregado")</script>';
        }
    } else {
        $item_array = array(
            'item_id'           =>  $_GET["id"],
            'item_name'         =>  $_POST["hidden_name"],
            'item_price'        =>  $_POST["hidden_price"],
            'item_quantity'     =>  $_POST["quantity"]
        );
        $_SESSION["shopping_cart"][0] = $item_array;
    }
}

if (isset($_GET["action"])) {
    if ($_GET["action"] == "delete") {
        foreach ($_SESSION["shopping_cart"] as $keys => $values) {
            if ($values["item_id"] == $_GET["id"]) {
                unset($_SESSION["shopping_cart"][$keys]);
                echo '<script>alert("Producto retirado")</script>';
                echo '<script>window.location="index.php"</script>';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>carro_compras</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <style>
        body {
            background-color: #f0f0f0;
        }
        .product-container {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .product-image {
            max-width: 100%;
            height: auto;
        }
        .product-name {
            color: #333;
            font-weight: bold;
            margin-top: 10px;
        }
        .product-price {
            color: #e74c3c;
            font-weight: bold;
            margin-top: 5px;
        }
        .btn-primary {
            background-color: #2980b9;
            border-color: #2980b9;
        }
        .btn-primary:hover {
            background-color: #3498db;
            border-color: #3498db;
        }
        .table {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .table th, .table td {
            border-color: #ddd;
        }
        .table th {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <br />
    <div class="container">
        <br />
        <br />
        <br />
        <h3 align="center"><a href="https://www.configuroweb.com/" title="Para más desarrollos ConfiguroWeb" style="color:#2980b9;text-decoration:none;">Para más desarrollos ConfiguroWeb</a></h3><br />
        <br /><br />
        <?php
        $query = "SELECT * FROM tbl_product ORDER BY id ASC";
        $result = mysqli_query($connect, $query);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
        ?>
                <div class="col-md-4">
                    <form method="post" action="index.php?action=add&id=<?php echo $row["id"]; ?>">
                        <div class="product-container">
                            <img src="images/<?php echo $row["image"]; ?>" class="img-responsive product-image" />
                            <h4 class="product-name"><?php echo $row["name"]; ?></h4>
                            <h4 class="product-price">$ <?php echo $row["price"]; ?></h4>
                            <input type="text" name="quantity" value="1" class="form-control" />
                            <input type="hidden" name="hidden_name" value="<?php echo $row["name"]; ?>" />
                            <input type="hidden" name="hidden_price" value="<?php echo $row["price"]; ?>" />
                            <input type="submit" name="add_to_cart" style="margin-top:5px;" class="btn btn-primary" value="Agregar Producto" />
                        </div>
                    </form>
                </div>
        <?php
            }
        }
        ?>
        <div style="clear:both"></div>
        <br />
        <h3>Información de la Orden</h3>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th width="40%">Nombre del Producto</th>
                    <th width="10%">Cantidad</th>
                    <th width="20%">Precio</th>
                    <th width="15%">Total</th>
                    <th width="5%">Acción</th>
                </tr>
                <?php
                if (!empty($_SESSION["shopping_cart"])) {
                    $total = 0;
                    foreach ($_SESSION["shopping_cart"] as $keys => $values) {
                ?>
                        <tr>
                            <td><?php echo $values["item_name"]; ?></td>
                            <td><?php echo $values["item_quantity"]; ?></td>
                            <td>$ <?php echo $values["item_price"]; ?></td>
                            <td>$ <?php echo number_format($values["item_quantity"] * $values["item_price"], 2); ?></td>
                            <td><a href="index.php?action=delete&id=<?php echo $values["item_id"]; ?>"><span class="text-danger">Quitar Producto</span></a></td>
                        </tr>
                    <?php
                        $total = $total + ($values["item_quantity"] * $values["item_price"]);
                    }
                    ?>
                    <tr>
                        <td colspan="3" align="right">Total</td>
                        <td align="right">$ <?php echo number_format($total, 2); ?></td>
                        <td></td>
                    </tr>
                <?php
                }
                ?>
            </table>
        </div>
    </div>
    <br />
</body>
</html>

<?php

?>