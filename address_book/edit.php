<?php
require './parts/connect_db.php';

// 取得資料的primary key
$sid = isset($_GET['sid']) ? intval($_GET['sid']) : 0;

if (empty($sid)) {
  header('Location: list.php');
  exit; //結束程式
}

$sql = "SELECT * FROM address_book WHERE sid={$sid}";
$rows = $pdo->query($sql)->fetch();
if (empty($rows)) {
  header('Location: list.php');
  exit;
}
# echo json_encode($rows, JSON_UNESCAPED_UNICODE);

$title = '新增';

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
          <h5 class="card-title">新增資料</h5>

          <form name="form1" onsubmit="sendData(event)">
            <div class="mb-3">
              <input type="hidden" name="sid" value="<?= $rows['sid'] ?>">
              <label for="name" class="form-label">姓名</label>
              <input type="text" class="form-control" id="name" name="name" value="<?= htmlentities($rows['name']) ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">email</label>
              <input type="text" class="form-control" id="email" name="email" value="<?= htmlentities($rows['email']) ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="mobile" class="form-label">mobile</label>
              <input type="text" class="form-control" id="mobile" name="mobile" value="<?= htmlentities($rows['mobile']) ?>">
              <div class="form-text"></div>
              <button class="btn btn-secondary" type="button">btn若沒設type 則預設為submit</button>
            </div>
            <div class="mb-3">
              <label for="birthday" class="form-label">birthday</label>
              <input type="date" class="form-control" id="birthday" name="birthday" value="<?= $rows['birthday'] ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="address" class="form-label">address</label>
              <textarea class="form-control" name="address" id="address" cols="30" rows="3"><?= $rows['address'] ?></textarea>
              <div class="form-text"></div>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
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
  const fields = [name_in, email_in, mobile_in];

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