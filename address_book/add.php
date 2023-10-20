<?php
require './parts/connect_db.php';

$pageName = 'add';
$title = '一日暢遊票券';

?>
<?php include './parts/html_head.php' ?>
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
          <h5 class="card-title">一日暢遊票券購買</h5>

          <form name="form1" onsubmit="sendData(event)">
            <div class="mb-3">
              <label for="name" class="form-label">姓名</label>
              <input type="text" class="form-control" id="name" name="name">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">email</label>
              <input type="text" class="form-control" id="email" name="email">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="mobile" class="form-label">mobile</label>
              <input type="text" class="form-control" id="mobile" name="mobile">
              <div class="form-text"></div>
            </div>
              <select class="form-tickets" name="tickets" id="tickets">
                <option selected>選擇票券</option>
                <option value="1">成人票</option>
                <option value="2">早鳥票</option>
                <option value="3">學生票</option>
                <option value="4">兒童票</option>
                <option value="5">敬老票</option>
                <option value="6">愛心票</option>
              </select>
              <br><br>
              <select class="form-catch" name="catch" id="catch">
                <option selected>取票方式</option>
                <option value="1">現場取票</option>
                <option value="2">超商取票</option>
                <option value="3">郵寄</option>
                <option value="4">電子票券取票</option>
              </select>
              <br><br>
            <button type="submit" class="btn btn-primary">送出</button>
          </form>

        </div>
      </div>
    </div>
  </div>


</div>

<?php include './parts/scripts.php' ?>
<script>
  const name_in = document.form1.name;
  const email_in = document.form1.email;
  const mobile_in = document.form1.mobile;
  const tickets_in = document.form1.tickets;
  const catch_in = document.form1.catch;
  const fields = [name_in, email_in, mobile_in, tickets_in, catch_in];

  function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
  }

  function validateMobile(mobile) {
    const re = /^09\d{2}-?\d{3}-?\d{3}$/;
    return re.test(mobile);
  }


  function sendData(e) {
    e.preventDefault(); // 不要讓表單以傳統的方式送出

    // 外觀要回復原來的狀態
    fields.forEach(field => {
      field.style.border = '1px solid #CCCCCC';
      field.nextElementSibling.innerHTML = '';
    })

    // TODO: 資料在送出之前, 要檢查格式
    let isPass = true; // 有沒有通過檢查
    if (name_in.value.length < 2) {
      isPass = false;
      name_in.style.border = '2px solid red';
      name_in.nextElementSibling.innerHTML = '請填寫正確的姓名';
    }

    if (!validateEmail(email_in.value)) {
      isPass = false;
      email_in.style.border = '2px solid red';
      email_in.nextElementSibling.innerHTML = '請填寫正確的 Email';
    }
    if (tickets_in.value = "選擇票券") {
      isPass = false;
      tickets_in.style.border = '2px solid red';
      tickets_in.nextElementSibling.innerHTML = "請選擇票券";
    }
    if (catch_in.value = "取票方式") {
      isPass = false;
      catch_in.style.border = '2px solid red';
      catch_in.nextElementSibling.innerHTML = "請選擇取票方式";
    }
    // 非必填
    if (mobile_in.value && !validateMobile(mobile_in.value)) {
      isPass = false;
      mobile_in.style.border = '2px solid red';
      mobile_in.nextElementSibling.innerHTML = '請填寫正確的手機號碼';
    }


    if (!isPass) {
      return; // 沒有通過就不要發送資料
    }
    // 建立只有資料的表單
    const fd = new FormData(document.form1);

    fetch('add-api.php', {
        method: 'POST',
        body: fd, // 送出的格式會自動是 multipart/form-data
      }).then(r => r.json())
      .then(data => {
        console.log({
          data
        });
        if (data.success) {
          alert('資料新增成功');
          location.href = "./list.php"
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