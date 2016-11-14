<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order attach page</title>
    <link href="/resource/css/discount_infi_page_style.css" rel="stylesheet" media="all">
</head>
<body>
<div id="main_container">
    <div class="order_id_input_form_container">
        <form action="" method="post">
            <p class="form_element">
                <label>Прикрепить код <?php echo $code;?> к заказу</label>
                <input id="input_order_id" type="number" placeholder="Только цифры" name="orderID"/>
                <input class="submit" type="submit" name="attachOrder" value="Прикрепить"/>
                <input class="came_back" type="submit" name="came_back" value="На предыдущую"/>
            </p>
        </form>
    </div>
    <?php if(isset($errorMessage)):?>
        <div class="result_container">
            <p class="negative_result"><?php echo $errorMessage;?></p>
        </div>
    <?php endif;?>
    <?php if(isset($isChanged)):?>
        <div class="allert_container">
            <?php if($isChanged === true) : ?>
                <p class="positive_result">Изменения успешно внесены</p>
            <?php elseif ($isChanged === false) : ?>
                <p class="negative_result">Не удалось внести изменения</p>
            <?php endif;?>
        </div>
    <?php endif;?>
</div>
</body>
</html>