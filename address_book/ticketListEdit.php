<?php
require './parts/connect_db.php';
$partName ='ticket';

// 取得資料的primary key
$tc2_id = isset($_GET['tc2_id']) ? intval($_GET['tc2_id']) : 0;

if (empty($tc2_id)) {
  header('Location: ticketList.php');
  exit; //結束程式
}

$sql = "SELECT * FROM ticketcategory2 WHERE tc2_id={$tc2_id}";
$rows = $pdo->query($sql)->fetch();
if (empty($rows)) {
  header('Location: ticketList.php');
  exit;
}
# echo json_encode($rows, JSON_UNESCAPED_UNICODE);

$title = '編輯票券種類';

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
          <h5 class="card-title">編輯票券種類</h5>

          <form name="form1" onsubmit="sendData(event)">
            <div class="mb-3">
              <input type="hidden" name="tc2_id" value="<?= $rows['tc2_id'] ?>">
              <label for="tc2_name" class="form-label">票券名稱</label>
              <input type="text" class="form-control" id="tc2_name" name="tc2_name" value="<?= htmlentities($rows['tc2_name']) ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="tc_amount" class="form-label">金額</label>
              <input type="text" class="form-control" id="tc_amount" name="tc_amount" value="<?= htmlentities($rows['tc_amount']) ?>">
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
  const tc2_name_in = document.form1.tc2_id;
  const tc_amount_in = document.form1.tc_amount;
  const fields = [tc2_name_in, tc_amount];

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
    /* if (tc1_id_in.value.length < 2) {
      isPass = false;
      tc1_id_in.style.border = '2px solid red';
      tc1_id_in.nextElementSibling.innerHTML = '請填寫正確的名稱';
    }  */
    if (tc2_name_in.value.length = 0) {
      isPass = false;
      tc2_name.style.border = '2px solid red';
      tc2_name.nextElementSibling.innerHTML = '請填寫正確的名稱';
    }

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

    fetch('ticketListEdit-api.php', {
        method: 'POST',
        body: fd, // 送出的格式會自動是 multipart/form-data
      }).then(r => r.json())
      .then(data => {
        console.log({
          data
        });
        if (data.success) {
          alert('資料編輯成功');
          location.href = "./ticketList.php"
        } else {
          alert('資料沒有修改');
          for(let n in data.errors){
            console.log(`n: ${n}`);
            if(document.form1[n]){
              const input = document.form1[n];
              input.style.border = '2px solid red';
              input.nextElementSibling.innerHTML = data.errors[n];
            }
          }
          location.href = "./ticketList.php"
        }

      })
      .catch(ex => console.log(ex))
  }
</script>
<?php include './parts/html_foot.php' ?>