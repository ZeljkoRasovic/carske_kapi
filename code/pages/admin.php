<?php
 require_once __DIR__.'/../components/default/session.php';
 if(!isset($_SESSION["id"]))
 {
  header("Location: ../pages/index.php");
  exit();
 }
 $admin=1;
 $title="Admin page";
 require __DIR__.'/../components/head/head.php';
?>
<style>
*
{
 box-sizing:border-box;
 max-width:100%;
}

.table-container
{
 width:100%;
 overflow-x:auto;
 -webkit-overflow-scrolling:touch;
 margin-bottom:1.5rem;
}

.table-container table
{
 width:100%;
 border-collapse:collapse;
 min-width:400px;
 font-size:0.9rem;
}

table th,table td
{
 padding:0.5rem;
 border:1px solid #ccc;
 text-align:left;
 white-space:nowrap;
}

table td
{
 word-break:break-word;
}

.responsive-form
{
 display:flex;
 flex-wrap:wrap;
 gap:1rem;
 justify-content:space-between;
 padding:0.2rem;
}

.responsive-form .form-group
{
 flex:1 1 30%;
 min-width:200px;
 display:flex;
 margin-top:1rem;
 flex-direction:column;
}

.responsive-form .form-buttons
{
 flex:1 1 100%;
 display:flex;
 flex-wrap:wrap;
 justify-content:flex-end;
 gap:0.5rem;
 margin:1rem 0;
}

.input
{
 padding:0.2rem;
 border-radius:1rem;
 border:1px solid #ccc;
 width:100%;
 font-size:1rem;
}

.button,a.button
{
 display:inline-block;
 padding:0.5rem 1rem;
 border-radius:1rem;
 background-color:#e9e9e9;
 color:#18191f;
 border:none;
 cursor:pointer;
 text-decoration:none;
 text-align:center;
}

.button:hover,a.button:hover
{
 background-color:#3d3f4f;
}

.elementBg h2
{
 font-size:1.3rem;
 word-wrap:break-word;
}

.flexCenter
{
 display:flex;
 flex-wrap:wrap;
 justify-content:center;
 align-items:center;
 text-align:center;
}

@media (max-width:600px)
{
 table th,table td
 {
  font-size:0.85rem;
  padding:0.4rem;
 }

 .responsive-form .form-group
 {
  flex:1 1 100%;
  min-width:0;
 }
}

@media (max-width:300px)
{
 html,body
 {
  font-size:14px;
  padding:0;
  margin:0;
 }

 .responsive-form
 {
  flex-direction:column;
  padding:0.4rem;
  gap:0.6rem;
 }

 .responsive-form .form-group
 {
  flex:1 1 100%;
  min-width:0;
 }

 .form-buttons
 {
  flex-direction:column;
  align-items:stretch;
 }

 .button,a.button
 {
  width:100%;
  padding:0.6rem;
  font-size:0.9rem;
 }

 table th,table td
 {
  font-size:0.8rem;
  padding:0.3rem;
 }

 h2
 {
  font-size:1.1rem;
 }

 select.input,input.input
 {
  font-size: 0.9rem;
 }
}
</style>
 <body>
  <?php require __DIR__.'/../components/nav/nav.php';?>
  <main class="bg">
<?php
require __DIR__."/../components/default/db.php";

function fetchDetectData()
{
 $query='SELECT * FROM detects;';
 $stmt=$GLOBALS['pdo']->prepare($query);
 $stmt->execute();
 return $stmt->fetchAll();
}

$detects=fetchDetectData();
$msg="";

if(isset($_POST['add_city']))
{
 $stmt=$GLOBALS['pdo']->prepare("INSERT INTO cities (cityName, postalCode, countryID) VALUES (?, ?, ?)");
 $stmt->execute([$_POST['cityName'], $_POST['postalCode'], $_POST['countryID']]);
 $msg="Grad dodat!";
}

if(isset($_POST['update_city']))
{
 $stmt=$GLOBALS['pdo']->prepare("UPDATE cities SET cityName=?, postalCode=?, countryID=? WHERE cityID=?");
 $stmt->execute([$_POST['cityName'], $_POST['postalCode'], $_POST['countryID'], $_POST['cityID']]);
 $msg="Grad izmenjen!";
}

if(isset($_GET['delete_city']))
{
 $stmt=$GLOBALS['pdo']->prepare("DELETE FROM cities WHERE cityID=?");
 $stmt->execute([$_GET['delete_city']]);
 $msg="Grad obrisan!";
}

if(isset($_GET['toggle_user']))
{
 $userID=$_GET['toggle_user'];
 $stmt=$GLOBALS['pdo']->prepare("UPDATE users SET userStatus = CASE WHEN userStatus='activated' THEN 'banned' ELSE 'activated' END WHERE userID=?");
 $stmt->execute([$userID]);
 $msg="Status korisnika promenjen!";
}

$countries=$GLOBALS['pdo']->query("SELECT * FROM countries ORDER BY countryName LIMIT 100")->fetchAll();
$countryFilter=isset($_GET['countryFilter'])?(int)$_GET['countryFilter']:0;

if($countryFilter>0)
{
 $stmt=$GLOBALS['pdo']->prepare("SELECT c.*, co.countryName FROM cities c LEFT JOIN countries co ON c.countryID=co.countryID WHERE c.countryID=? ORDER BY cityName");
 $stmt->execute([$countryFilter]);
 $cities=$stmt->fetchAll();
}
else
{
 $cities=$GLOBALS['pdo']->query("SELECT c.*, co.countryName FROM cities c LEFT JOIN countries co ON c.countryID=co.countryID ORDER BY cityName LIMIT 100")->fetchAll();
}

$users=$GLOBALS['pdo']->query("SELECT * FROM users ORDER BY userEmail")->fetchAll();

$editCity=null;
if(isset($_GET['edit_city']))
{
 $stmt=$GLOBALS['pdo']->prepare("SELECT * FROM cities WHERE cityID=? LIMIT 100");
 $stmt->execute([$_GET['edit_city']]);
 $editCity=$stmt->fetch();
}
?>
<div class="flexCenter">
 <h2 class="elementBg">Admin Panel</h2>
</div>
<?php if($msg) echo "<p><b>$msg</b></p>"; ?>
<div class="flexCenter">
 <h2 class="elementBg">Gradovi</h2>
</div>
<div class="flexCenter">
 <form method="get" class="elementBg">
 <br>
 <label for="countryF">Filter po državi:</label><br>
  <select name="countryFilter" class="input select" id="countryF" onchange="this.form.submit()">
   <option value="0">Sve države</option>
   <?php foreach($countries as $co): ?>
   <option value="<?= $co['countryID'] ?>" <?= ($countryFilter==$co['countryID'])?'selected':'' ?>>
   <?= htmlspecialchars($co['countryName']) ?>
   </option>
   <?php endforeach; ?>
  </select>
  <br><br>
 </form>
</div>
<br>
<?php if($editCity): ?>
<form method="post" class="elementBg responsive-form">
 <input type="hidden" name="cityID" value="<?= $editCity['cityID'] ?>">
 <div class="form-group">
  <label for="cityNI">Naziv:</label>
  <input type="text" name="cityName" class="input" id="cityNI" value="<?= htmlspecialchars($editCity['cityName']) ?>" required>
 </div>
 <div class="form-group">
  <label for="postalCI">Poštanski kod:</label>
  <input type="text" name="postalCode" id="postalCI" class="input" value="<?= htmlspecialchars($editCity['postalCode']) ?>" required>
 </div>
 <div class="form-group">
  <label for="countryI">Država:</label>
  <select name="countryID" class="input" id="countryI">
   <?php foreach($countries as $co): ?>
   <option value="<?= $co['countryID'] ?>" <?= $co['countryID']==$editCity['countryID']?'selected':'' ?>>
   <?= htmlspecialchars($co['countryName']) ?>
   </option>
   <?php endforeach; ?>
  </select>
 </div>
 <div class="form-buttons">
  <button type="submit" name="update_city" class="button">Sačuvaj izmene</button>
  <a href="admin.php" class="button">Otkaži</a>
 </div>
</form>
<?php else: ?>
<form method="post" class="elementBg responsive-form">
 <input type="hidden" name="cityID" value="">
 <div class="form-group">
  <label for="cityNI2">Naziv:</label>
  <input type="text" class="input" id="cityNI2" name="cityName" required>
 </div>
  <div class="form-group">
   <label for="postalCI2">Poštanski kod:</label>
    <input type="text" class="input" id="postalCI2" name="postalCode" required>
  </div>
  <div class="form-group">
    <label for="countryII2">Država:</label>
    <select name="countryID" class="input" id="countryII2">
      <?php foreach($countries as $co): ?>
      <option value="<?= $co['countryID'] ?>"><?= htmlspecialchars($co['countryName']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="form-buttons">
    <button type="submit" class="button" name="add_city">Dodaj</button>
  </div>
</form>
<?php endif; ?>
<br>
<div class="table-container elementBg">
<table id="Cities">
 <thead>
  <tr><th>ID</th><th>Naziv</th><th>Poštanski kod</th><th>Država</th><th>Akcija</th></tr>
 </thead>
 <tbody>
 <?php foreach($cities as $c): ?>
  <tr>
   <td><?= $c['cityID'] ?></td>
   <td><?= htmlspecialchars($c['cityName']) ?></td>
   <td><?= htmlspecialchars($c['postalCode']) ?></td>
   <td><?= htmlspecialchars($c['countryName']) ?></td>
   <td>
    <a href="?edit_city=<?= $c['cityID'] ?>">Izmeni</a> | 
    <a href="?delete_city=<?= $c['cityID'] ?>" onclick="return confirm('Obrisati grad?')">Obriši</a>
   </td>
  </tr>
  <?php endforeach; ?>
 </tbody>
</table>
</div>
<div class="table-container elementBg">
<h2>Korisnici</h2>
<table id="Users">
 <thead>
  <tr><th>ID</th><th>Email</th><th>Status</th><th>Akcija</th></tr>
 </thead>
 <tbody>
  <?php foreach($users as $u): ?>
  <tr>
   <td><?= $u['userID'] ?></td>
   <td><?= htmlspecialchars($u['userEmail']) ?></td>
   <td class="<?= $u['userStatus'] ?>"><?= $u['userStatus'] ?></td>
   <td><a href="?toggle_user=<?= $u['userID'] ?>">Promeni status</a></td>
  </tr>
  <?php endforeach; ?>
 </tbody>
</table>
</div>
<br>
<br>
<section class="elementBg">
 <h2>IP Address Records</h2>
 <br>
 <div class="table-container">
 <table id="Ip" class="elementBg">
  <thead>
   <tr>
    <th>ID</th>
    <th>IP Address</th>
    <th>Operating System</th>
    <th>Device Type</th>
    <th>Country ID</th>
    <th>City ID</th>
    <th>Time zone</th>
    <th>ISP</th>
    <th>User Agent</th>
    <th>Date time</th>
   </tr>
  </thead>
  <tbody>
   <?php if(!empty($detects)): ?>
   <?php foreach ($detects as $detect): ?>
   <tr>
    <td><?php echo htmlspecialchars($detect['id_detect']); ?></td>
    <td><?php echo htmlspecialchars($detect['ip_address']); ?></td>
    <td><?php echo htmlspecialchars($detect['operation_system']); ?></td>
    <td><?php echo htmlspecialchars($detect['device_type']); ?></td>
    <td><?php echo htmlspecialchars($detect['countryID']); ?></td>
    <td><?php echo htmlspecialchars($detect['cityID']); ?></td>
    <td><?php echo htmlspecialchars($detect['timeZone']); ?></td>
    <td><?php echo htmlspecialchars($detect['isp']); ?></td>
    <td><?php echo htmlspecialchars($detect['http_user_agent']); ?></td>
    <td><?php echo htmlspecialchars($detect['date_time']); ?></td>
   </tr>
   <?php endforeach; ?>
   <?php else: ?>
   <tr>
    <td colspan="5">No records found</td>
   </tr>
   <?php endif; ?>
  </tbody>
 </table>
 </div>
</section>
<script src="../components/libraries/jquery/jquery-3.7.1.min.js"></script>
<script src="../components/libraries/dataTables/dataTables.js"></script>
<script>
$(document).ready(function()
{
 $('#Cities').DataTable();
});
$(document).ready(function()
{
 $('#Users').DataTable();
});
$(document).ready(function()
{
 $('#Ip').DataTable();
});
</script>
</main>
</body>
</html>
