<?php
require './parts/connect_db.php';

$pageName = 'orderAdd';
$title = '訂單票券';
$partName = 'ticket';

$sql = "SELECT * FROM orderstate";
$sql1 = "SELECT * FROM ticketcategory1";
$sql2 = "SELECT * FROM ticketcategory2";
$rows = $pdo->query($sql)->fetchAll();
$rows1 = $pdo->query($sql1)->fetchAll();
$rows2 = $pdo->query($sql2)->fetchAll();


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
          <h5 class="card-title">訂單票券新增</h5>

          <form name="form1" onsubmit="sendData(event)">
            <div class="mb-3">
              <label for="user_name" class="form-label">姓名</label>
              <input type="text" class="form-control" id="user_name" name="user_name">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">新增票券類別</div>
            <div class="input-group mb-3">
              <span class="input-group-text">票券類別</span>
              <select class="form-select" name="tc1_id" id="cate1" onchange="generateCate2List()">
                <?php foreach ($rows1 as $r1) :
                  // if ($r1['tc1_id'] == '3') : 
                ?>
                  <option value="<?= $r1['tc1_id'] ?>"><?= $r1['tc1_name'] ?></option>
                <?php
                // endif;
                endforeach ?>
              </select>
            </div>
            <div class="input-group mb-3">
              <span class="input-group-text">票券名稱</span>
              <select class="form-select" name="tc2_id" id="cate2" onchange="generateamount()"></select>
              <!-- <?php foreach ($rows1 as $r1) :
                      if ($r1['tc1_id'] == $rows['tc1_id']) :
                    ?> -->
                <option value="<?= $r1['tc2_id'] ?>"><?= $r1['tc2_name'] ?></option>
              <!-- <?php
                      endif;
                    endforeach ?> -->
            </div>
            <label for="tc_amount" class="form-label">金額：</label>
            <span class="mb-3" id="tc_amount" name="tc_amount"></span>
            <div class="mb-3">
              <label for="orderTime" class="form-label">票券日期</label>
              <input type="date" class="form-control " id="orderTime" name="orderTime">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="orderState_id" class="form-label">付款狀態</label>
              <select class="form-select" id="orderState_id" name="orderState_id" required="required">
                <?php foreach ($rows as $r) : ?>
                  <option value="<?= $r['orderState_id'] ?>" <?= $r['orderState_id'] == 1 ? 'selected' : "disabled" ?>><?= $r['stateName'] ?></option>
                <?php
                endforeach ?>
              </select>
              <div class="form-text"></div>
            </div>
            <!-- datetimepicker -->
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
  const user_name_in = document.form1.user_name;
  const tc1_name_in = document.form1.tc1_id;
  const tc2_name_in = document.form1.tc2_id;
  const tc_amount_in = document.form1.tc_amount;
  const orderTime_in = document.form1.orderTime;
  const orderState_id_in = document.form1.orderState_id;
  // const email_in = document.form1.email;
  // const mobile_in = document.form1.mobile;
  // const fields = [user_name_in, tc1_name_in, tc2_name_in, tc_amount_in, orderTime_in, orderState_id_in];

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
    // fields.forEach(field => {
    //   field.style.border = '1px solid #CCCCCC';
    //   field.nextElementSibling.innerHTML = '';
    // })

    // TODO: 資料在送出之前, 要檢查格式
    let isPass = true; // 有沒有通過檢查
    if (user_name.value.length < 2) {
      isPass = false;
      user_name.style.border = '2px solid red';
      user_name.nextElementSibling.innerHTML = '請填寫正確的姓名';
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

    fetch('orderAdd-api.php', {
        method: 'POST',
        body: fd, // 送出的格式會自動是 multipart/form-data
      }).then(r => r.json())
      .then(data => {
        console.log({
          data
        });
        if (data.success) {
          alert('資料新增成功');
          location.href = "./orderList.php"
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

  const initVals = {
    cate1: 1,
    cate2: 1
  };

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

  cate1.value = initVals.cate1; // 設定第一層的初始值
  generateCate2List(); // 生第二層
  cate2.value = initVals.cate2; // 設定第二層的初始值
  generateamount();
</script>
<?php include './parts/html_foot.php' ?>