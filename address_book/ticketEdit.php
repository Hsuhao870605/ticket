<?php
require './parts/connect_db.php';
$partName = 'ticket';

// 取得資料的primary key
$sid = isset($_GET['sid']) ? intval($_GET['sid']) : 0;

if (empty($sid)) {
  header('Location: ticketList.php');
  exit; //結束程式
}

$sql = "SELECT * FROM productlist WHERE sid={$sid}";
$rows = $pdo->query($sql)->fetch();
if (empty($rows)) {
  header('Location: ticketList.php');
  exit;
}
# echo json_encode($rows, JSON_UNESCAPED_UNICODE);

$sql1 = "SELECT * FROM ticketcategory1";
$sql2 = "SELECT * FROM ticketcategory2";


$rows1 = $pdo->query($sql1)->fetchAll();
$rows2 = $pdo->query($sql2)->fetchAll();

$title = '編輯資料';

foreach ($rows2 as $ticketcategory2) {
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
          <h5 class="card-title">編輯資料</h5>

          <form name="form1" onsubmit="sendData(event)">
            <div class="input-group mb-3">
            <input type="hidden" name="sid" value="<?= $rows['sid'] ?>">
              <span class="input-group-text">票券類別</span>
              <select class="form-select" name="tc1_id" id="cate1" onchange="generateCate2List()">
                <?php foreach ($rows1 as $r1) : ?>
                  <!-- 透過三元運算子判斷 主類的選項中 有符合 $ticketcategory1 的 id 就 selected  -->
                  <option value="<?= $r1['tc1_id'] ?>" <?= $r1['tc1_id'] == $ticketcategory1 ? 'selected' : '' ?>><?= $r1['tc1_name'] ?></option>
                <?php
                // endif;
                endforeach ?>
              </select>
            </div>
            <div class="input-group mb-3">
              <span class="input-group-text">票券名稱</span>
              <select class="form-select" name="tc2_id" id="cate2" onchange="generateamount()" ?>
                <?php foreach ($rows2 as $r2) : ?>
                  <!-- 透過 $ticketcategory1 先把 不是同一個大類的子選項去除 -->
                  <?php if ($r2['tc1_id'] == $ticketcategory1) : ?>
                    <!-- 透過三元運算子判斷 在次要分類中 有符合 tc2_id 的選項就加上 selected -->
                    <option value="<?= $r2['tc2_id'] ?>" <?= $r2['tc2_id'] == $rows['tc2_id'] ? 'selected' : '' ?>><?= $r2['tc2_name'] ?></option>
                  <?php endif ?>
                <?php endforeach ?>
              </select>
            </div>
            <label for="tc_amount" class="form-label">金額：</label>
            <span class="mb-3" id="tc_amount" name="tc_amount"></span>
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
  const description_in = document.form1.description;

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


    // TODO: 資料在送出之前, 要檢查格式
    let isPass = true; // 有沒有通過檢查
    if (description_in.value.length < 2) {
      isPass = false;
      description_in.style.border = '2px solid red';
      description_in.nextElementSibling.innerHTML = '請填寫正確的描述';
    }


    if (!isPass) {
      return; // 沒有通過就不要發送資料
    }
    // 建立只有資料的表單
    const fd = new FormData(document.form1);

    fetch('ticketEdit-api.php', {
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
          for (let n in data.errors) {
            console.log(`n: ${n}`);
            if (document.form1[n]) {
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

  // const initVals = {
  //   cate1: 1,
  //   cate2: 1
  // };

  const cates = <?= json_encode($rows2, JSON_UNESCAPED_UNICODE) ?>;

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