<?php
require './parts/connect_db.php';
$partName = 'ticket';

// 取得資料的primary key
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if (empty($order_id)) {
  header('Location: orderList.php');
  exit; //結束程式
}

$sql = "SELECT * FROM orderlist WHERE order_id={$order_id}";
$sql1 = "SELECT * FROM orderstate";
$sql2 = "SELECT * FROM ticketcategory1";
$sql3 = "SELECT * FROM ticketcategory2";

$rows = $pdo->query($sql)->fetch();
$rows1 = $pdo->query($sql1)->fetchAll();
$rows2 = $pdo->query($sql2)->fetchAll();
$rows3 = $pdo->query($sql3)->fetchAll();
if (empty($rows)) {
  header('Location: orderList.php');
  exit;
}
if (empty($rows1)) {
  header('Location: orderList.php');
  exit;
}
# echo json_encode($rows, JSON_UNESCAPED_UNICODE);

$title = '訂單資料編輯';

foreach ($rows3 as $ticketcategory2) {
  if ($ticketcategory2['tc2_id'] == $rows['tc2_id']) {
    $ticketcategory1 = $ticketcategory2['tc1_id'];
  }
}

?>
<?php include './parts/html_head.php' ?>
<?php include './parts/main_part.php' ?>
<?php include './parts/navbar.php' ?>
<style>
  form .form-text {
    color: red;
  }
</style>
<div class="container">
  <div class="row">
    <div class="col-6">
      <div class="card">

        <div class="card-body">
          <h5 class="card-title">訂單資料編輯</h5>

          <form name="form1" onsubmit="sendData(event)">
            <div class="mb-3">
              <input type="hidden" name="order_id" value="<?= $rows['order_id'] ?>">
              <label for="user_name" class="form-label">姓名</label>
              <input type="text" class="form-control" id="user_name" name="user_name" value="<?= htmlentities($rows['user_name']) ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">新增票券類別</div>
            <div class="input-group mb-3">
              <span class="input-group-text">票券類別</span>
              <select class="form-select" name="tc1_id" id="cate1" onchange="generateCate2List()">
                <?php foreach ($rows2 as $r2) : ?>
                  <option value="<?= $r2['tc1_id'] ?>" <?= $r2['tc1_id'] == $ticketcategory1 ? 'selected' : '' ?>><?= $r2['tc1_name'] ?></option>
                <?php
                endforeach ?>
              </select>
            </div>
            <div class="input-group mb-3">
              <span class="input-group-text">票券名稱</span>
              <select class="form-select" name="tc2_id" id="cate2" onchange="generateamount()" ?>
                <?php foreach ($rows3 as $r3) : ?>
                  <?php if ($r3['tc1_id'] == $ticketcategory1) : ?>
                    <option value="<?= $r3['tc2_id'] ?>" <?= $r3['tc2_id'] == $rows['tc2_id'] ? 'selected' : '' ?>><?= $r3['tc2_name'] ?></option>
                  <?php endif ?>
                <?php endforeach ?>
              </select>
            </div>
            <label for="tc_amount" class="form-label">金額：</label>
            <span class="mb-3" id="tc_amount" name="tc_amount"></span>
            <div class="mb-3">
              <label for="orderTime" class="form-label">票券日期</label>
              <input type="date" class="form-control " id="orderTime" name="orderTime" value="<?= htmlentities($rows['orderTime']) ?>">
              <div class="form-text"></div>
            </div>
            <!-- datetimepicker -->
            <div class="mb-3">
              <label for="orderState_id" class="form-label">付款狀態</label>
              <select class="form-select" id="orderState_id" name="orderState_id" required="required">
                <?php foreach ($rows1 as $r) : ?>
                  <option value="<?= $r['orderState_id'] ?>" <?= $r['orderState_id'] == $rows['orderState_id'] ? 'selected' : "" ?>><?= $r['stateName'] ?></option>
                <?php
                endforeach ?>
              </select>
              <div class="form-text"></div>
            </div>

            <button type="submit" class="btn btn-primary">送出</button>
          </form>

        </div>
      </div>
    </div>
  </div>


</div>

<?php include './parts/scripts.php' ?>
<script>
  const user_name_in = document.form1.user_name;

  /*function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
  }

  function validateMobile(mobile) {
    const re = /^09\d{2}-?\d{3}-?\d{3}$/;
    return re.test(mobile);
  }
*/

  function sendData(e) {
    e.preventDefault(); // 不要讓表單以傳統的方式送出

    // 外觀要回復原來的狀態
    /* fields.forEach(field => {
      field.style.border = '1px solid #CCCCCC';
      field.nextElementSibling.innerHTML = '';
    }) */

    // TODO: 資料在送出之前, 要檢查格式
    let isPass = true; // 有沒有通過檢查
    if (user_name_in.value.length < 1) {
      isPass = false;
      user_name_in.style.border = '2px solid red';
      user_name_in.nextElementSibling.innerHTML = '請填寫正確的姓名';
    }

    /* if (!validateEmail(email_in.value)) {
      isPass = false;
      t_name_in.style.border = '2px solid red';
      t_name_in.nextElementSibling.innerHTML = '請填寫正確的 類型';
    }
    // 非必填
    if (mobile_in.value && !validateMobile(mobile_in.value)) {
      isPass = false;
      amount_in.style.border = '2px solid red';
      amount_in.nextElementSibling.innerHTML = '請填寫正確的金額';
    } */


    if (!isPass) {
      return; // 沒有通過就不要發送資料
    }
    // 建立只有資料的表單
    const fd = new FormData(document.form1);

    fetch('orderEdit-api.php', {
        method: 'POST',
        body: fd, // 送出的格式會自動是 multipart/form-data
      }).then(r => r.json())
      .then(data => {
        console.log({
          data
        });
        if (data.success) {
          alert('資料編輯成功');
          location.href = "./orderList.php"
        } else {
          alert('資料沒有修改');
          for (let n in data.errors) {
            console.log(`n: ${n}`);
            if (document.form1[n]) {
              const input = document.form1[n];
              input.style.border = '2px solid red';
              input.nextElementSibling.innerHTML = data.errors[n];
            }
          }
        }

      })
      .catch(ex => console.log(ex))
  }

  // const initVals = {
  //   cate1: 1,
  //   cate2: 1
  // };

  const cates = <?= json_encode($rows3, JSON_UNESCAPED_UNICODE) ?>;

  const cate1 = document.querySelector('#cate1')
  const cate2 = document.querySelector('#cate2')
  const amount = document.querySelector('#amount')



  function generateCate2List() { //呼叫generateCate2List()
    const cate1Val = cate1.value; // 主分類的值

    let str = "";
    //b;
    for (let item of cates) {
      if (+item.tc1_id === +cate1Val) { //+ cate1轉成字串
        str += `<option value="${item.tc2_id}">${item.tc2_name}</option>`;
        //a;
      }
    }

    cate2.innerHTML = str;
    generateamount();
  }

  function generateamount() { //呼叫generateCate2List()
    const cate2Val = cate2.value; // 主分類的值

    let str = "";
    //b;
    for (let item of cates) {
      if (+item.tc2_id === +cate2Val) { //+ cate1轉成字串
        str += `${item.tc_amount}`;
        //a;
      }
    }

    tc_amount.innerHTML = str;

  }

  // cate1.value = initVals.cate1; // 設定第一層的初始值
  // generateCate2List(); // 生第二層
  // cate2.value = initVals.cate2; // 設定第二層的初始值
  generateamount();
</script>
<?php include './parts/html_foot.php' ?>