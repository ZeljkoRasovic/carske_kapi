<?php
 require_once __DIR__.'/../components/default/session.php';
 if(!isset($_SESSION["id"]))
 {
  header("Location: ../pages/index.php");
  exit();
 }
 $title="Control center page";
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
   <div class="flexCenter">
    <h2 class="elementBg">Agency panel</h2>
   </div>
<?php
 require __DIR__.'/../components/default/db.php';

 $msg="";

 if(isset($_POST['add_destination']))
 {
  $stmt=$GLOBALS["pdo"]->prepare("INSERT INTO destinations (destinationName, destinationDescription, cityID) VALUES (?, ?, ?)");
  $stmt->execute([$_POST['name'], $_POST['description'], $_POST['cityID']]);
  $msg="Destination is added!";
 }

 if(isset($_POST['update_destination']))
 {
  $stmt=$GLOBALS["pdo"]->prepare("UPDATE destinations SET destinationName=?, destinationDescription=?, cityID=? WHERE destinationID=?");
  $stmt->execute([$_POST['name'], $_POST['description'], $_POST['cityID'], $_POST['id']]);
  $msg="Destination is edited!";
 }

 if(isset($_GET['delete_destination']))
 {
  $destinationID=$_GET['delete_destination'];
  $images=$GLOBALS["pdo"]->prepare("SELECT destinationImageName FROM destinationImages WHERE destinationID=?");
  $images->execute([$destinationID]);
  foreach($images->fetchAll() as $img)
  {
   $filePath="/../upload/destinations/".$img['destinationImageName'];
   if(file_exists($filePath)) unlink($filePath);
  }

  $stmt=$GLOBALS["pdo"]->prepare("DELETE FROM destinationImages WHERE destinationID=?");
  $stmt->execute([$destinationID]);

  $stmt=$GLOBALS["pdo"]->prepare("DELETE FROM destinations WHERE destinationID=?");
  $stmt->execute([$destinationID]);

  $msg="Destination and all it\'s pictures are deleted!";
 }

 if(isset($_POST['upload_destination_image']))
 {
  $destinationID=$_POST['destinationID'];
  if(!empty($_FILES['image']['name']))
  {
   $targetDir="../upload/destinations/";
   $fileName=time()."_".basename($_FILES["image"]["name"]);
   $targetFile=$targetDir.$fileName;
   $fileType=strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
   $allowedTypes=['jpg','jpeg','png','gif','svg'];

   if(in_array($fileType, $allowedTypes))
   {
    if(move_uploaded_file($_FILES["image"]["tmp_name"],$targetFile))
    {
     $stmt=$GLOBALS["pdo"]->prepare("INSERT INTO destinationImages (destinationID, destinationImageStatus, destinationImageName) VALUES (?, 'active', ?)");
     $stmt->execute([$destinationID, $fileName]);
     $msg="Picture of destination is uploaded!";
    }
    else
    {
     $msg="Error at upload of the file.";
    }
   }
   else
   {
    $msg="Allowed formats: jpg, jpeg, png, gif, svg.";
   }
  }
  else
  {
   $msg="You must select a file.";
  }
 }

 if(isset($_GET['delete_destination_image']))
 {
  $imgID=$_GET['delete_destination_image'];
  $stmt=$GLOBALS["pdo"]->prepare("SELECT destinationImageName FROM destinationImages WHERE destinationImageID=?");
  $stmt->execute([$imgID]);
  $img=$stmt->fetch();

  if($img)
  {
   $filePath="../upload/destinations/".$img['destinationImageName'];
   if(file_exists($filePath)) unlink($filePath);

   $stmt=$GLOBALS["pdo"]->prepare("DELETE FROM destinationImages WHERE destinationImageID=?");
   $stmt->execute([$imgID]);
   $msg="Pictures of destination are deleted!";
  }
 }

 $destFilter="";
 $destParams=[];

 if(!empty($_GET['filter_destination']))
 {
  $filterVal="%".$_GET['filter_destination']."%";
  $destFilter=" WHERE d.destinationName LIKE :filter OR c.cityName LIKE :filter OR co.countryName LIKE :filter ";
  $destParams=[':filter'=>$filterVal];
 }

 $destQuery="SELECT d.*, c.cityName, co.countryName, (SELECT destinationImageName FROM destinationImages i WHERE i.destinationID=d.destinationID ORDER BY i.destinationImageDateTimeAdded ASC LIMIT 1) AS imageName FROM destinations d JOIN cities c ON d.cityID=c.cityID JOIN countries co ON c.countryID=co.countryID ".$destFilter." ORDER BY d.destinationName ASC";

 $stmt=$GLOBALS["pdo"]->prepare($destQuery);
 $stmt->execute($destParams);
 $destinationsList=$stmt->fetchAll();

 $cities=$GLOBALS["pdo"]->query("SELECT c.cityID, c.cityName, c.countryID, co.countryName FROM cities c JOIN countries co ON c.countryID=co.countryID ORDER BY c.cityName")->fetchAll();

 $destStats=[];
 $stats=[];
 $destStatsFilter="";
 $destStatsParams=[];

 if(!empty($_GET['filter_dest_stats']))
 {
  $destStatsFilter=" WHERE d.destinationName LIKE :filterName OR c.cityName LIKE :filterCity OR co.countryName LIKE :filterCountry ";
  $filterValue="%".$_GET['filter_dest_stats']."%";
  $destStatsParams=
  [
   ':filterName'=>$filterValue,
   ':filterCity'=>$filterValue,
   ':filterCountry'=>$filterValue
  ];
 }

 $destStatsQuery="SELECT d.destinationName, c.cityName, co.countryName, p.searchCount, p.viewCount, p.destinationPopularityLastDateTimeUpdated FROM destinations d JOIN cities c ON d.cityID=c.cityID JOIN countries co ON c.countryID=co.countryID JOIN destinationPopularity p ON d.destinationID=p.destinationID ".$destStatsFilter." ORDER BY p.viewCount DESC";

 $stmt=$GLOBALS["pdo"]->prepare($destStatsQuery);
 $stmt->execute($destStatsParams);
?>
<div class="flexCenter">
 <h2 class="elementBg">CRUD destination</h2>
</div>
<div class="flexCenter">
 <form method="post" class="elementBg">
  <br>
  <input type="hidden" name="id">
  <label for="Name">Name:</label>
  <br>
  <input type="text" name="name" class="input" id="Name" required>
  <br>
  <br>
  <label for="Description">Description:</label>
  <br>
  <textarea name="description" id="Description" class="input"></textarea>
  <br>
  <br>
  <label for="countryID">Country:</label>
  <br>
  <select name="countryID" id="countryID" class="input" required onchange="filterCities()">
  <?php 
   $countries=$GLOBALS["pdo"]->query("SELECT * FROM countries ORDER BY countryName")->fetchAll();
   foreach($countries as $co):
  ?>
    <option value="<?= $co['countryID']?>"><?= htmlspecialchars($co['countryName'])?></option>
  <?php endforeach;?>
  </select>
  <br>
  <br>
  <label for="cityID">City:</label>
  <select name="cityID" id="cityID" class="input" required>
  <?php foreach($cities as $c):?>
   <option value="<?= $c['cityID']?>" data-country="<?= $c['countryID']?>">
    <?= htmlspecialchars($c['cityName']." (".$c['countryName'].")")?>
   </option>
  <?php endforeach;?>
  </select>
  <script>
   function filterCities()
   {
    const selectedCountry = document.getElementById("countryID").value;
    const citySelect = document.getElementById("cityID");
    for(let opt of citySelect.options)
    {
     if(opt.getAttribute("data-country")===selectedCountry)
     {
      opt.style.display="block";
     }
     else
     {
      opt.style.display="none";
     }
    }
    for(let opt of citySelect.options)
    {
     if(opt.style.display==="block")
     {
      citySelect.value=opt.value;
      break;
     }
    }
   }
   window.onload=filterCities;
  </script>
  <br>
  <br>
  <div class="flexCenter">
   <button type="submit" name="add_destination" class="button">Add</button>
  </div>
  <br>
 </form>
</div>
<br>
<br>
<br>
<div class="table-container elementBg">
<table cellpadding="5" class="flexCenter">
 <tr>
  <th>ID</th><th>Name</th><th>Description</th><th>City</th><th>Country</th><th>Photo</th><th>Actions</th>
 </tr>
 <?php foreach($destinationsList as $d):?>
 <tr>
  <td><?= $d['destinationID']?></td>
  <td><?= htmlspecialchars($d['destinationName'])?></td>
  <td><?= htmlspecialchars($d['destinationDescription'])?></td>
  <td><?= htmlspecialchars($d['cityName'])?></td>
  <td><?= htmlspecialchars($d['countryName'])?></td>
  <td>
   <?php if(!empty($d['imageName'])): ?>
    <img src="../upload/destinations/<?= $d['imageName']?>" width="80">
   <?php else: ?>
    <img src="../upload/destinations/DEFAULTdESTINATIONpICTURE.svg" width="80">
   <?php endif; ?>
   <br>
   <a href="#uploadDestination" onclick="document.getElementById('destinationID').value=<?= $d['destinationID'] ?>">Add picture</a>
  </td>
  <td>
   <a href="?delete_destination=<?= $d['destinationID'] ?>" onclick="return confirm('Delete a destination and all it\'s pictures?')">Delete</a>
  </td>
 </tr>
 <?php endforeach;?>
</table>
</div>
<br>
<div class="flexCenter">
 <h2 id="uploadDestination" class="elementBg">Upload a destination picture</h2>
</div>
<div class="flexCenter">
 <form method="post" enctype="multipart/form-data" class="elementBg">
  <br>
  <label for="destinationID">Destination:</label>
  <br>
  <select name="destinationID" id="destinationID" class="input" required>
   <?php foreach($destinationsList as $d):?>
    <option value="<?= $d['destinationID']?>"><?= htmlspecialchars($d['destinationName'])?></option>
   <?php endforeach;?>
  </select>
  <br>
  <br>
  <label for="PhotoFile">Choose a picture:</label>
  <br>
  <br>
  <input type="file" id="PhotoFile" name="image" required>
  <br>
  <br>
  <div class="flexCenter">
   <button type="submit" name="upload_destination_image" class="button">Upload</button>
  </div>
  <br>
 </form>
</div>
<br>
<div class="flexCenter">
 <h3 class="elementBg">Photos of destination</h3>
</div>
<div class="table-container elementBg">
<table cellpadding="5" class="flexCenter">
 <tr>
  <th>ID</th><th>Destination</th><th>Photo</th><th>Status</th><th>Date</th><th>Actions</th>
 </tr>
 <?php
 $destImgs=$GLOBALS["pdo"]->query("SELECT i.*, d.destinationName FROM destinationImages i JOIN destinations d ON i.destinationID=d.destinationID ORDER BY i.destinationImageDateTimeAdded DESC")->fetchAll();
 foreach($destImgs as $img): ?>
 <tr>
  <td><?= $img['destinationImageID']?></td>
  <td><?= htmlspecialchars($img['destinationName'])?></td>
  <td><img src="../upload/destinations/<?= $img['destinationImageName']?>" width="100"></td>
  <td><?= $img['destinationImageStatus']?></td>
  <td><?= $img['destinationImageDateTimeAdded']?></td>
  <td><a href="?delete_destination_image=<?= $img['destinationImageID']?>" onclick="return confirm('Obrisati ovu fotografiju?')">Delete</a></td>
 </tr>
 <?php endforeach; ?>
</table>
</div>
<br>
<br>
<br>
<div class="table-container elementBg">
<table cellpadding="5" class="flexCenter">
 <tr>
  <th>Destination</th><th>City</th><th>Country</th><th>Number of searches</th><th>Number of views</th><th>Last updated</th>
 </tr>
 <?php foreach($destStats as $s): ?>
 <tr>
  <td><?= htmlspecialchars($s['destinationName'])?></td>
  <td><?= htmlspecialchars($s['cityName'])?></td>
  <td><?= htmlspecialchars($s['countryName'])?></td>
  <td><?= $s['searchCount']?></td>
  <td><?= $s['viewCount']?></td>
  <td><?= $s['destinationPopularityLastDateTimeUpdated']?></td>
 </tr>
 <?php endforeach;?>
</table>
</div>
<?php
 if(isset($_POST['add']))
 {
  $stmt=$GLOBALS["pdo"]->prepare("INSERT INTO landmarks (landmarkName, landmarkDescription, landmarkAddress, destinationID) VALUES (?, ?, ?, ?)");
  $stmt->execute([$_POST['name'], $_POST['description'], $_POST['address'], $_POST['destinationID']]);
  $msg="Znamenitost dodata!";
 }

 if(isset($_POST['update']))
 {
  $stmt=$GLOBALS["pdo"]->prepare("UPDATE landmarks SET landmarkName=?, landmarkDescription=?, landmarkAddress=?, destinationID=? WHERE landmarkID=?");
  $stmt->execute([$_POST['name'], $_POST['description'], $_POST['address'], $_POST['destinationID'], $_POST['id']]);
  $msg="Znamenitost izmenjena!";
 }

 if(isset($_GET['delete_landmark']))
 {
  $landmarkID=$_GET['delete_landmark'];

  $images=$GLOBALS["pdo"]->prepare("SELECT landmarkImageName FROM landmarkImages WHERE landmarkID=?");
  $images->execute([$landmarkID]);
  foreach($images->fetchAll() as $img)
  {
   $filePath="../upload/landmarks/".$img['landmarkImageName'];
   if(file_exists($filePath))unlink($filePath);
  }
  $stmt=$GLOBALS["pdo"]->prepare("DELETE FROM landmarkImages WHERE landmarkID=?");
  $stmt->execute([$landmarkID]);

  $stmt=$GLOBALS["pdo"]->prepare("DELETE FROM landmarks WHERE landmarkID=?");
  $stmt->execute([$landmarkID]);
  $msg="Znamenitost i sve njene slike obrisane!";
 }

 if(isset($_POST['upload_image']))
 {
  $landmarkID=$_POST['landmarkID'];
  if(!empty($_FILES['image']['name']))
  {
   $targetDir="../upload/landmarks/";
   $fileName=time()."_".basename($_FILES["image"]["name"]);
   $targetFile=$targetDir.$fileName;
   $fileType=strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
   $allowedTypes=['jpg','jpeg','png','gif','svg'];

   if(in_array($fileType, $allowedTypes))
   {
    if(move_uploaded_file($_FILES["image"]["tmp_name"],$targetFile))
    {
     $stmt=$GLOBALS["pdo"]->prepare("INSERT INTO landmarkImages (landmarkID, landmarkImageStatus, landmarkImageName) VALUES (?, 'active', ?)");
     $stmt->execute([$landmarkID, $fileName]);
     $msg="Fotografija uspešno uploadovana!";
    }
    else
    {
     $msg="Greška pri uploadu fajla.";
    }
   }
   else
   {
    $msg="Dozvoljeni formati: jpg, jpeg, png, gif, svg.";
   }
  }
  else
  {
   $msg="Morate odabrati fajl.";
  }
 }

 if(isset($_GET['delete_image']))
 {
  $imgID=$_GET['delete_image'];
  $stmt=$GLOBALS["pdo"]->prepare("SELECT landmarkImageName FROM landmarkImages WHERE landmarkImageID=?");
  $stmt->execute([$imgID]);
  $img=$stmt->fetch();

  if($img)
  {
   $filePath="../upload/landmarks/".$img['landmarkImageName'];

   if(file_exists($filePath))unlink($filePath);

   $stmt=$GLOBALS["pdo"]->prepare("DELETE FROM landmarkImages WHERE landmarkImageID=?");
   $stmt->execute([$imgID]);
   $msg="Fotografija obrisana!";
  }
 }

 $filter="";
 $params=[];

 if(!empty($_GET['filter_landmark']))
 {
  $filterVal="%" . $_GET['filter_landmark'] . "%";
  $filter=" WHERE l.landmarkName LIKE :filter OR d.destinationName LIKE :filter ";
  $params=[':filter' => $filterVal];
 }

 $query="SELECT l.*, d.destinationName, (SELECT landmarkImageName FROM landmarkImages i WHERE i.landmarkID=l.landmarkID ORDER BY i.landmarkImageDateTimeAdded ASC LIMIT 1) AS imageName FROM landmarks l LEFT JOIN destinations d ON l.destinationID=d.destinationID ".$filter." ORDER BY l.landmarkName ASC";

 $stmt=$GLOBALS["pdo"]->prepare($query);
 $stmt->execute($params);
 $landmarks=$stmt->fetchAll();
?>
<div class="flexCenter">
 <?php if (!empty($msg)) echo "<p><b>$msg</b></p>"; ?>
</div>
<div class="flexCenter">
 <h2 class="elementBg">CRUD znamenitosti</h2>
</div>
<div class="flexCenter">
 <form method="post" class="elementBg">
  <br>
  <input type="hidden" name="id">
  <label for="NameI">Naziv:</label><br><input type="text" name="name" class="input" id="NameI" required><br><br>
  <label for="AddressI">Adresa:</label><br><input type="text" name="address" class="input" id="AddressI" required><br><br>
  <label for="DescriptionI">Opis:</label><br><textarea name="description" class="input" id="DescriptionI"></textarea><br><br>
  <label for="DestinationI">Destinacija:</label><br>
  <select name="destinationID" id="DestinationI" class="input"required>
   <?php foreach($destinationsList as $d): ?>
    <option value="<?= $d['destinationID'] ?>"><?= htmlspecialchars($d['destinationName']) ?></option>
   <?php endforeach; ?>
  </select>
 <br>
 <br>
 <div class="flexCenter">
  <button type="submit" name="add" class="button">Dodaj</button>
 </div>
 <br>
 </form>
</div>
 <br>
 <br>
 <br>
<div class="table-container elementBg">
 <table cellpadding="5" class="flexCenter">
 <tr>
  <th>ID</th><th>Naziv</th><th>Adresa</th><th>Opis</th><th>Destinacija</th><th>Fotografija</th><th>Akcije</th>
 </tr>
 <?php foreach($landmarks as $l): ?>
 <tr>
  <td><?= $l['landmarkID']?></td>
  <td><?= htmlspecialchars($l['landmarkName'])?></td>
  <td><?= htmlspecialchars($l['landmarkAddress'])?></td>
  <td><?= htmlspecialchars($l['landmarkDescription'])?></td>
  <td><?= htmlspecialchars($l['destinationName'])?></td>
  <td>
   <?php if(!empty($l['imageName'])):?>
    <img src="../upload/landmarks/<?= $l['imageName']?>" width="80">
   <?php else: ?>
    <img src="../upload/landmarks/DEFAULTpROFILEpICTURE.svg" width="80">
   <?php endif; ?>
    <br>
    <a href="#upload" onclick="document.getElementById('landmarkID').value=<?= $l['landmarkID'] ?>">+ Dodaj sliku</a>
    </td>
    <td>
     <a href="?delete_landmark=<?= $l['landmarkID'] ?>" onclick="return confirm('Obrisati znamenitost i sve njene slike?')">Obriši</a>
    </td>
   </tr>
  <?php endforeach; ?>
 </table>
</div>
 <br>
 <div class="flexCenter">
  <h2 id="upload" class="elementBg">Upload fotografije</h2>
 </div>
 <div class="flexCenter">
  <form method="post" enctype="multipart/form-data" class="elementBg">
   <br><label for="landmarkID">Znamenitost:</label><br>
   <select name="landmarkID" id="landmarkID" class="input" required>
    <?php foreach($landmarks as $l): ?>
     <option value="<?= $l['landmarkID'] ?>"><?= htmlspecialchars($l['landmarkName']) ?></option>
    <?php endforeach; ?>
   </select><br><br>
   <label for="photoI">Izaberi fotografiju:</label><br><br>
   <input type="file" name="image" id="photoI" required><br><br>
   <div class="flexCenter">
    <button type="submit" name="upload_image" class="button">Upload</button>
   </div>
   <br>
  </form>
 </div>
 <br>
 <div class="flexCenter">
  <h3 class="elementBg">Postojeće fotografije</h3>
 </div>
<div class="table-container elementBg">
 <table cellpadding="5" class="flexCenter">
 <tr>
  <th>ID</th><th>Znamenitost</th><th>Fotografija</th><th>Status</th><th>Datum</th><th>Akcija</th>
 </tr>
 <?php
 $images=$GLOBALS["pdo"]->query("SELECT i.*, l.landmarkName FROM landmarkImages i JOIN landmarks l ON i.landmarkID=l.landmarkID ORDER BY i.landmarkImageDateTimeAdded DESC")->fetchAll();
foreach($images as $img):?>
 <tr>
  <td><?= $img['landmarkImageID']?></td>
  <td><?= htmlspecialchars($img['landmarkName'])?></td>
  <td><img src="../upload/landmarks/<?= $img['landmarkImageName']?>" width="100"></td>
  <td><?= $img['landmarkImageStatus']?></td>
  <td><?= $img['landmarkImageDateTimeAdded']?></td>
  <td><a href="?delete_image=<?= $img['landmarkImageID']?>" onclick="return confirm('Obrisati ovu fotografiju?')">Obriši</a></td>
 </tr>
 <?php endforeach; ?>
</table>
</div>
<br>
<br>
<br>
<div class="table-container elementBg">
<table cellpadding="5" class="flexCenter">
 <tr>
  <th>Znamenitost</th><th>Destinacija</th><th>Broj pretraga</th><th>Broj pregleda</th><th>Zadnje ažuriranje</th>
 </tr>
 <?php foreach($stats as $s): ?>
 <tr>
  <td><?= htmlspecialchars($s['landmarkName']) ?></td>
  <td><?= htmlspecialchars($s['destinationName']) ?></td>
  <td><?= $s['searchCount'] ?></td>
  <td><?= $s['viewCount'] ?></td>
  <td><?= $s['landmarkPopularityLastDateTimeUpdated'] ?></td>
 </tr>
 <?php endforeach; ?>
</table>
</div>
</main>
</body>
</html>
