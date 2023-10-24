<?php
require './parts/connect_db.php';
$partName='ticket';

// 取得資料的primary key
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if (empty($order_id)) {
  header('Location: orderList.php');
  exit; //結束程式
}

$sql = "SELECT * FROM orderlist WHERE order_id={$order_id}";
$sql1 = "SELECT * FROM orderstate";

$rows = $pdo->query($sql)->fetch();
$rows1 = $pdo->query($sql1)->fetchAll();
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
            <div class="mb-3">
              <label for="t_name" class="form-label">票券名稱</label>
              <input type="text" class="form-control" id="t_name" name="t_name" value="<?= htmlentities($rows['t_name']) ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="amount" class="form-label">金額</label>
              <input type="text" class="form-control" id="amount" name="amount" value="<?= htmlentities($rows['amount']) ?>">
              <div class="form-text"></div>
            </div>
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
                  <option value="<?= $r['orderState_id'] ?>" <?= $r['orderState_id'] == 1 ? 'selected' : "" ?>><?= $r['stateName'] ?></option>
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
  const t_name_in = document.form1.t_name;
  const amount_in = document.form1.amount;
  const orderTime_in = document.form1.orderTime;
  const endTime_in = document.form1.endTime;
  const stateName_in = document.form1.stateName;
  const fields = [user_name_in, t_name_in, amount_in, orderTime_in, endTime_in, stateName_in];

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
    if (user_name_in.value.length < 2) {
      isPass = false;
      user_name_in.style.border = '2px solid red';
      user_name_in.nextElementSibling.innerHTML = '請填寫正確的名稱';
    }
    if (t_name_in.value.length < 2) {
      isPass = false;
      t_name.style.border = '2px solid red';
      t_name.nextElementSibling.innerHTML = '請填寫正確的類型';
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
</script>
<?php include './parts/html_foot.php' ?>