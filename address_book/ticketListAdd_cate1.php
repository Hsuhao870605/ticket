<?php
require './parts/connect_db.php';

$pageName = 'ticketListAdd';
$title = '一日暢遊票券';
$partName='ticket';

$sql = "SELECT * FROM ticketCategory1";

$rows = $pdo->query($sql)->fetchAll();

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
          <h5 class="card-title">新增名稱種類</h5>

          <form name="form1" onsubmit="sendData(event)">
        <div class="mb-3">
          <label for="tc1_name" class="form-label">票券名稱</label>
          <input type="text" class="form-control" id="tc1_name" name="tc1_name">
          <div class="form-text"></div>
        </div>
        <!--<div class="mb-3">
              <label for="email" class="form-label">email</label>
              <input type="text" class="form-control" id="email" name="email">
              <div class="form-text"></div>
            </div> 
            <div class="mb-3">
              <label for="mobile" class="form-label">mobile</label>
              <input type="text" class="form-control" id="mobile" name="mobile">
              <div class="form-text"></div>
            </div> 
            <br><br> -->
        <button type="submit" class="btn btn-primary">送出</button>
        </form>

      </div>
    </div>
  </div>
</div>


</div>

<?php include './parts/scripts.php' ?>
<script>
  const tc1_name_in = document.form1.tc1_name;

  // const email_in = document.form1.email;
  // const mobile_in = document.form1.mobile;
  const fields = [tc1_name_in];

  /* function validateEmail(email) {
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
    /* if (t_name.value.length < 2) {
      isPass = false;
      t_name.style.border = '2px solid red';
      t_name.nextElementSibling.innerHTML = '請填寫正確的類型';
    } */
    if (tc1_name.value.length < 2) {
      isPass = false;
      tc1_name_in.style.border = '2px solid red';
      tc1_name_in.nextElementSibling.innerHTML = '請填寫正確的名稱';
    }

    /*if (!validateEmail(email_in.value)) {
      isPass = false;
      email_in.style.border = '2px solid red';
      email_in.nextElementSibling.innerHTML = '請填寫正確的 Email';
    } 
    // 非必填
    if (mobile_in.value && !validateMobile(mobile_in.value)) {
      isPass = false;
      mobile_in.style.border = '2px solid red';
      mobile_in.nextElementSibling.innerHTML = '請填寫正確的手機號碼';
    } */


    if (!isPass) {
      return; // 沒有通過就不要發送資料
    }
    // 建立只有資料的表單
    const fd = new FormData(document.form1);

    fetch('ticketListAdd_cate1-api.php', {
        method: 'POST',
        body: fd, // 送出的格式會自動是 multipart/form-data
      }).then(r => r.json())
      .then(data => {
        console.log({
          data
        });
        if (data.success) {
          alert('資料新增成功');
          location.href = "./ticketList_cate1.php"
        } else {
          // alert('資料有誤');
          for (let n in data.errors) {
            console.log(`n: ${n}`);
            if (document.form1[n]) {
              const input = document.form1[n];
              input.style.border = '2px solid red';
              input.nextElementSibling.innerHTML = '請填寫正確的手機號碼';
            }
          }
        }

      })
      .catch(ex => console.log(ex))
  }
</script>
<?php include './parts/html_foot.php' ?>