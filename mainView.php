<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Discount info page</title>
    <link href="/resource/css/discount_infi_page_style.css" rel="stylesheet" media="all">
</head>
<body>
<div id="main_container">
    <div class="discount_code_input_form_container">
        <form action="" method="post">
            <p class="form_element">
                <label>Введите код скидки</label>
                <input id="input_discount_code" type="text" name="code" placeholder="Только латинские буквы, цифры и симфолы -_" pattern="^[-0-9a-zA-Z_]+$"/>
                <input class="submit" type="submit" name="submit" value="Получить информацию"/>
            </p>
        </form>
    </div>
    <?php if(isset($isChanged)):?>
    <div class="allert_container">
        <?php if($isChanged === true) : ?>
            <p class="positive_result">Изменения успешно внесены</p>
        <?php elseif ($isChanged === false) : ?>
            <p class="negative_result">Не удалось внести изменения</p>
        <?php endif;?>
    </div>
    <?php endif;?>
    <?php if(isset($discount) && !isset($cameBack)): ?>
        <?php if($discount !== false) :?>
    <div id="discount_main_container">
        <div id="discount_info_container">
            <h2 class="info_label">Информация о скидке</h2>
            <table id="discount_info" border="2">
            <?php foreach ($discount as $key => $value) :?>
                <tr>
                    <td><?php echo $key; ?></td>
                    <td><?php echo $value; ?></td>
                </tr>
            <?php endforeach;?>
            </table>
        </div>
        <div class="discount_change_status_form_container">
            <form method="post" action="">
                <p class="form_element">
                    <label>Вы можете изменить статус кода скидки</label>
                </p>
                <p class="form_element">
                    <label>Активный</label>
                    <input type="checkbox" name="Active" value="Active"<?php if($active == 1) echo 'checked';?>/>
                </p>
                <p class="form_element">
                    <label>Использован</label>
                    <input type="checkbox" name="Is Used" value="Is used" <?php if($isUsed == 1) echo 'checked';?>/>
                </p>
                <p class="form_element">
                    <input class="submit" type="submit" name="Change" value="Изменить">
                </p>
            </form>
        </div>
    </div>
        <?php else :?>
            <div class="result_container">
                <p class="negative_result">Не удалось обнаружить скидку с кодом <?php echo $code;?></p>
            </div>
        <?php endif; ?>
    <?php endif;?>
    <?php if(isset($orders)): ?>
    <div id="order_main_contaier">
        <div>
        <?php if(isset($attachOrder)) :?>
            <form method="post" action="">
                <label>Прикрепить код <?php echo $code;?> к заказу</label>
                <input class="submit" type="submit" name="add" value="Перейти">
            </form>
        <?php endif;?>
        </div>
        <div id="orders_info_container">
            <h2 class="info_label">Заказы со скидочным кодом <?php echo $code;?></h2>
            <table class="order_info" border="2">
            <?php foreach ($orders as $index => $order) :?>
                <tr>
                    <td>Код заказа</td>
                    <td><?php echo $order['id']; ?></td>
                </tr>
            <?php endforeach;?>
            </table>
        </div>
    </div>
    <?php endif;?>
</div>
</body>
</html>
