<?php
require './parts/connect_db.php';
$partName = 'ticket';

// 取得資料的primary key
$sid = isset($_GET['sid']) ? intval($_GET['sid']) : 0;

if (empty($sid)) {
  header('Location: list.php');
  exit; //結束程式
}

$sql = "SELECT * FROM productlist WHERE sid={$sid}";
$rows = $pdo->query($sql)->fetch();
if (empty($rows)) {
  header('Location: list.php');
  exit;
}
# echo json_encode($rows, JSON_UNESCAPED_UNICODE);

$sql1 = "SELECT * FROM ticketcategory1";
$sql2 = "SELECT * FROM ticketcategory2";


$rows1 = $pdo->query($sql1)->fetchAll();
$rows2 = $pdo->query($sql2)->fetchAll();

$title = '編輯資料';

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
          <h5 class="card-title">編輯資料</h5>

          <form name="form1" onsubmit="sendData(event)">
            <div class="mb-3">
              <label for="tc1_id" class="form-label">票券種類</label>
              <select class="form-select" id="tc1_id" name="tc1_id" required="required">
                <?php foreach ($rows1 as $r1) : ?>
                  <option value="<?= $r1['tc1_id'] ?>" <?= $r1['tc1_id'] == $rows['tc1_id'] ? 'selected' : "" ?>><?= $r1['tc1_name'] ?></option>
                <?php
                endforeach ?>
              </select>
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="tc2_id" class="form-label">票券名稱</label>
              <select class="form-select" id="tc2_id" name="tc2_id" required="required">
                <?php foreach ($rows2 as $r2) : ?>
                  <option value="<?= $r2['tc2_id'] ?>" <?= $r2['tc2_id'] == $rows['tc2_id'] ? 'selected' : "" ?>><?= $r2['tc2_name'] ?></option>
                <?php
                endforeach ?>
              </select>
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="amount" class="form-label">金額</label>
              <input type="text" class="form-control" id="amount" name="amount" value="<?= htmlentities($rows['amount']) ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="beginTime" class="form-label">開始時間</label>
              <input type="date" class="form-control " id="beginTime" name="beginTime" value="<?= htmlentities($rows['beginTime']) ?>">
              <div class="form-text"></div>
            </div>
            <!-- datetimepicker -->
            <label for="endTime" class="form-label">結束時間</label>
            <input type="date" class="form-control" id="endTime" name="endTime" value="<?= htmlentities($rows['endTime']) ?>">
            <div class="form-text"></div>
            <div class="mb-3">
              <label for="description" class="form-label">描述</label>
              <textarea class="form-control" name="description" id="description" cols="30" rows="3"><?= htmlentities($rows['description']) ?></textarea>
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
  const tc1_name_in = document.form1.tc1_id;
  const tc2_id_in = document.form1.tc2_id;
  const amount_in = document.form1.amount;
  const beginTime_in = document.form1.beginTime;
  const endTime_in = document.form1.endTime;
  const description_in = document.form1.description;
  const fields = [tc1_name_in, tc2_id_in, amount, beginTime_in, endTime_in, description_in];

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
    if (description_in.value.length < 2) {
      isPass = false;
      description_in.style.border = '2px solid red';
      description_in.nextElementSibling.innerHTML = '請填寫正確的描述';
    } 
    /* if (tc2_id_in.value.length < 2) {
      isPass = false;
      tc2_id.style.border = '2px solid red';
      tc2_id.nextElementSibling.innerHTML = '請填寫正確的類型';
    } */

    /* if (!validateEmail(email_in.value)) {
      isPass = false;
      tc2_id_in.style.border = '2px solid red';
      tc2_id_in.nextElementSibling.innerHTML = '請填寫正確的 類型';
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

    fetch('edit-api.php', {
        method: 'POST',
        body: fd, // 送出的格式會自動是 multipart/form-data
      }).then(r => r.json())
      .then(data => {
        console.log({
          data
        });
        if (data.success) {
          alert('資料編輯成功');
          location.href = "./list.php"
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
          location.href = "./list.php"
        }

      })
      .catch(ex => console.log(ex))
  }
</script>
<?php include './parts/html_foot.php' ?>