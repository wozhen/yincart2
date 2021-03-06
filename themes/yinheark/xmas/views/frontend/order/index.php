<?php

use yii\helpers\Html;
use yii\helpers\Url;
use Kiwi\Kiwi;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

list($path, $link) = $this->getAssetManager()->publish('@themes/front/web/css/cart');
$this->registerCssFile($link . '/core.css', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile($link . '/box.css', ['depends' => [\yii\web\JqueryAsset::className()]]);

list($path, $link) = $this->getAssetManager()->publish('@yincart/order/web/js');
$this->registerJsFile($link . '/order.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>

<!-- Main Container Starts -->
<div id="main-container" class="container">
    <!-- Breadcrumb Starts -->
    <ol class="breadcrumb">
        <li><a href="<?= Url::to(['/']) ?>">首页</a></li>
        <li class="active">结算</li>
    </ol>
    <!-- Breadcrumb Ends -->
    <!-- Main Heading Starts -->
    <h2 class="main-heading text-center">
        结算
    </h2>
    <!-- Main Heading Ends -->

    <form>
        <div class="breadcrumb address-panel">
            <div class="box-title container_24"><span
                    style="float:right"><?php echo Html::a('管理收货地址', array('/customer/delivery-address'), array('target' => '_blank')) ?></span>收货地址
            </div>
            <div class="box-content">
                <?php list($addressList, $defaultAddress) = Kiwi::getCustomerAddress()->getAddressList(); ?>
                <?= Html::radioList('address', $defaultAddress, $addressList, ['separator' => '<br />']); ?>
            </div>
        </div>
        <div class="breadcrumb">
            <div class="box-title container_24">支付方式</div>
            <div class="box-content" style="vertical-align:middle;">
                <!--        --><?php
                //        $cri = new CDbCriteria(array(
                //            'condition' => 'enabled = 1'
                //        ));
                //        $paymentMethod = PaymentMethod::model()->findAll($cri);
                //        $list = CHtml::listData($paymentMethod, 'payment_method_id', 'name');
                //        echo CHtml::radioButtonList('payment_method_id', '0', $list);
                //
                ?>

                <input type="radio" value="alipay" name="payment" checked>支付宝
            </div>
        </div>

        <?php //$imageHelper=new ImageHelper(); ?>
        <div class="breadcrumb">
            <div class="box-title container_24">商品列表</div>
            <div class="box-content cart container_24">
                <table id="list-div-box" class="table">
                    <tr style="background:#F3F3F3;">
                        <th class="col-xs-3">图片</th>
                        <th class="col-xs-3">名称</th>
                        <!--                <th class="col-xs-3">属性</th>-->
                        <th class="col-xs-1">价格</th>
                        <th class="col-xs-1">数量</th>
                        <th class="col-xs-1">小计</th>
                    </tr>
                    <?php
                    foreach ($cartItems as $cartItem) {

                        $key = $cartItem->data['key'];
                        /** @var \extensions\sales\models\CustomerSales $customer_sale */
                        $customerSaleClass = Kiwi::getCustomerSalesClass();
                        $customer_sale = $customerSaleClass::find()->where(['key' => $key])->one();

                        $item = $cartItem->item;
                        if (isset($item)) {
                            $pictures = explode(',',$item->pictures);
                            ?>
                            <tr><?php
                                if ($item->pictures){
                                $picUrl = is_array($pictures)?$pictures[0]:$pictures;
                                ?>
                                <td><?php echo Html::img($picUrl,['style' => "width : 80px; height:80px"]);
                                    } else {
                                        echo Yii::t('leather', '该商品没有上传图片');
                                    } ?></td>
                                <td><?php echo $item->name; ?></td>
                                <td><?php echo $customer_sale->sale_price; ?></td>
                                <td><?php echo $cartItem->qty; ?></td>
                                <td><?php echo $customer_sale->sale_price * $cartItem->qty; ?></td>
                            </tr>
                        <?php
                        } else {
                            ?>
                            <tr>
                                <td colspan="6" style="padding:10px">您的购物车是空的!</td>
                            </tr>
                        <?php
                        }
                    }?>
                    <tr>
                        <td colspan="6" style="padding:10px;text-align:right">
                            运费：<?php echo Kiwi::getShoppingCart()->getShippingFee(); ?> 元
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" style="padding:10px;text-align:right">
                            总计：<?php echo Kiwi::getShoppingCart()->getTotal(); ?> 元
                        </td>
                    </tr>
                </table>

            </div>
            <div class="box-content">
                <div class="memo" style="float:left"><h3>
                        给卖家留言：</h3>
                    <textarea id="memo" name="memo" placeholder="选填，可以告诉卖家您对商品的特殊要求，如：颜色、尺码等" rows="5"></textarea>
                </div>
            </div>

            <div class="box-content">
                <?= Html::button('确认订单',
                    [
                        'class' => 'btn btn-danger pull-right create-order',
                        'data-create' => true, 'data-url' => Url::to(['order/order-save']),
                        'style' => "line-height:20px;margin-right:150px;margin-bottom:30px;"
                    ]) ?>
            </div>
        </div>
    </form>
</div>
<!-- Main Container Ends -->