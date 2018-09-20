<?php
//y引入配置文件
require_once '../config.php';
session_start();
function login()
{
  if (empty($_POST['email'])) {
    $GLOBALS['error_message'] = '请输入邮箱！';
    return;
  }
  if (empty($_POST['password'])) {
    $GLOBALS['error_message'] = '请输入密码！';
    return;
  }
  $email=$_POST['email'];
  $password=$_POST['password'];
  $conn=mysqli_connect(DB_HOST,DB_USER_NAME,DB_PASSWORD,DB_NAME);
  if (!$conn) {
    exit('<h1>数据库连接失败！</h1>');
  }
  $query=mysqli_query($conn,"SELECT email,`password` FROM users WHERE '{$email}'='hyf' limit 1;");
  if (!$query) {
    $GLOBALS['error_message'] = '您输入的用户名不存在！';
    return;
  }
  $user=mysqli_fetch_assoc($query);
  if (empty($user)) {
    $GLOBALS['error_message'] = '您输入用户名和密码不正确！';
    return;
  }
  if ($user['password']!==$password) {
    $GLOBALS['error_message'] = '用户密码错误！';
    return;
  }
  $_SESSION['user_logined']=$user;
  header('Location:/admin/');
}
if ($_SERVER['REQUEST_METHOD']==='POST') {
  login();
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Sign in &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <link rel="stylesheet" type="text/css" href="/static/assets/vendors/animate/animate.min.css">
</head>
<body>
  <div class="login">
    <form class="login-wrap <?php echo isset($GLOBALS['error_message'])?' shake animated':''; ?>" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" autocomplete='off' novalidate>
      <img class="avatar" src="/static/assets/img/default.png">
      <?php if (isset($GLOBALS['error_message'])): ?>
      <div class="alert alert-danger">
        <strong>错误！</strong><?php echo $GLOBALS['error_message']; ?>
      </div>
      <?php endif ?>
      <div class="form-group">
        <label for="email" class="sr-only">邮箱</label>
        <input id="email" name="email" type="email" class="form-control" placeholder="邮箱" autofocus value="<?php echo isset($_POST['email'])?$_POST['email']:''; ?>">
      </div>
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input id="password" name="password" type="password" class="form-control" placeholder="密码">
      </div>
      <button class="btn btn-primary btn-block">登 录</button>
    </form>
  </div>
</body>
</html>
