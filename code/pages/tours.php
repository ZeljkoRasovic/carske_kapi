<?php
 require_once __DIR__.'/../components/default/session.php';
 if(!isset($_SESSION["id"]))
 {
  header("Location: ../pages/index.php");
  exit();
 }
 $title="Tours page";
 $tours=1;
 require __DIR__.'/../components/head/head.php';

 if(!isset($_SESSION["id"]))
 {
  $userID = 0;
 }
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
  require_once __DIR__.'/../components/default/session.php';
  require __DIR__."/../components/default/db.php";

  $userID=$_SESSION["id"];
  $msg="";

  if(isset($_POST['create_tour']))
  {
   $pdo->beginTransaction();
   try
   {
    $stmt=$pdo->prepare("INSERT INTO tours (userID, destinationID) VALUES (?, NULL)");
    $stmt->execute([$userID]);
    $tourID=$pdo->lastInsertId();

    if(!empty($_POST['destinations']))
    {
     $stmtDest=$pdo->prepare("INSERT INTO tourDestinations (tourID, destinationID) VALUES (?, ?)");
     foreach($_POST['destinations'] as $destID)
     {
      $stmtDest->execute([$tourID, $destID]);
     }
    }
    $pdo->commit();
    $msg="Tura kreirana!";
   }
   catch(Exception $e)
   {
    $pdo->rollBack();
    $msg="Greška: ".$e->getMessage();
   }
  }
  if(isset($_GET['delete_tour']))
  {
   $stmt=$pdo->prepare("DELETE FROM tours WHERE tourID=? AND userID=?");
   $stmt->execute([$_GET['delete_tour'], $userID]);
   $msg="Tura obrisana!";
  }

  $destinations=$pdo->query("SELECT d.destinationID, d.destinationName, c.cityName, co.countryName FROM destinations d LEFT JOIN cities c ON d.cityID=c.cityID LEFT JOIN countries co ON c.countryID=co.countryID ORDER BY d.destinationName")->fetchAll();

  $tours=$pdo->prepare("SELECT * FROM tours WHERE userID=? ORDER BY tourDateTimeAdded DESC");
  $tours->execute([$userID]);
  $tours=$tours->fetchAll();
  $tourDestinations=[];

  if($tours)
  {
   $stmt=$pdo->prepare("SELECT td.tourID, d.destinationName, c.cityName, co.countryName FROM tourDestinations td JOIN destinations d ON td.destinationID=d.destinationID LEFT JOIN cities c ON d.cityID=c.cityID LEFT JOIN countries co ON c.countryID=co.countryID WHERE td.tourID=?");
   foreach($tours as $t)
   {
    $stmt->execute([$t['tourID']]);
    $tourDestinations[$t['tourID']]=$stmt->fetchAll();
   }
  }
?>
<div class="flexCenter">
<h2 class="elementBg">Ture</h2>
</div>
<?php if($msg)echo"<p><b>$msg</b></p>"; ?>

<div class="flexCenter">
<form method="post" class="elementBg">
<div class="flexCenter">
 <h3>Kreiraj novu turu</h3>
</div>
 <p>Izaberi destinacije (drži CTRL/SHIFT za više):</p>
 <select name="destinations[]" class="input" multiple size="6" required>
 <?php
  foreach($destinations as $d): ?>
   <option value="<?= $d['destinationID'] ?>">
    <?= htmlspecialchars($d['destinationName']." (".$d['cityName'].", ".$d['countryName'].")") ?>
   </option>
   <?php endforeach; ?>
  </select>
  <br><br>
  <div class="flexCenter">
   <button type="submit" class="button" name="create_tour">Kreiraj turu</button>
  </div>
  <br>
 </form>
</div>
<br>
<br>
<hr>
<br>
<div class="flexCenter">
 <h3 class="elementBg">Moje ture</h3>
</div>
<div class="table-container elementBg">
<table class="flexCenter">
 <tr><th>ID</th><th>Datum</th><th>Destinacije</th><th>Akcija</th></tr>
 <?php foreach($tours as $t): ?>
 <tr>
  <td><?= $t['tourID'] ?></td>
  <td><?= $t['tourDateTimeAdded'] ?></td>
  <td>
  <?php if(!empty($tourDestinations[$t['tourID']])): ?>
   <ul>
   <?php foreach($tourDestinations[$t['tourID']] as $dest): ?>
    <li><?= htmlspecialchars($dest['destinationName']." (".$dest['cityName'].", ".$dest['countryName'].")") ?></li>
    <?php endforeach; ?>
   </ul>
   <?php else: ?>
    (nema destinacija)
   <?php endif; ?>
  </td>
  <td><a href="?delete_tour=<?= $t['tourID'] ?>" onclick="return confirm('Obrisati turu?')">Obriši</a></td>
 </tr>
 <?php endforeach; ?>
</table>
</div>
<br>
</main>
</body>
</html>
