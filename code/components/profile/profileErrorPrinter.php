<?php

require_once __DIR__.'/../default/db.php';
require __DIR__.'/../profile/dinamicSelect.php';
require_once __DIR__.'/../default/session.php';

function checkProfileErrors()
{
 if(isset($_SESSION["errorsAtProfile"]))
 {
  $errors=$_SESSION['errorsAtProfile'];

  foreach($errors as $error)
  {
   echo'<div class="flexCenter">';
   echo' <p class="elementBg">'.$error.'</p>';
   echo'</div>';
  }
  unset($_SESSION['errorsAtSignup']);
 }
 else if(isset($_GET["edit"]) && $_GET["edit"]==="success")
 {
  echo'<div class="flexCenter">';
  echo' <div class="elementBg">You have uccessfully updated the profile!</div>';
  echo'</div>';
 }
 else if(isset($_GET["edit"]) && $_GET["edit"]==="failedToChangeUser")
 {
  echo'<div class="flexCenter">';
  echo' <div class="elementBg">We have failed to edit your profile!</div>';
  echo'</div>';
 }
 else if(isset($_GET["passwordChange"]) && $_GET["passwordChange"]==="passwordIsUpdated")
 {
  echo'<div class="flexCenter">';
  echo' <div class="elementBg">You have uccessfully updated the password of your profile!</div>';
  echo'</div>';
 }
 else if(isset($_GET["deleteProfile"]) && $_GET["deleteProfile"]==="failed")
 {
  echo'<div class="flexCenter">';
  echo' <div class="elementBg">We failed to delete your profile!</div>';
  echo'</div>';
 }
 else if(isset($_GET["image"]) && $_GET["image"]==="fileError")
 {
  echo'<div class="flexCenter">';
  echo' <div class="elementBg">Image upload error!</div>';
  echo'</div>';
 }
 else if(isset($_GET["image"]) && $_GET["image"]==="fileIsNotUploaded")
 {
  echo'<div class="flexCenter">';
  echo' <div class="elementBg">Image is not uploaded!</div>';
  echo'</div>';
 }
 else if(isset($_GET["image"]) && $_GET["image"]==="moveFailed")
 {
  echo'<div class="flexCenter">';
  echo' <div class="elementBg">Moving image from temp location to upload location failed!</div>';
  echo'</div>';
 }
 else if(isset($_GET["image"]) && $_GET["image"]==="fileIsNotAImage")
 {
  echo'<div class="flexCenter">';
  echo' <div class="elementBg">Uploaded file is not a image!</div>';
  echo'</div>';
 }
 else if(isset($_GET["image"]) && $_GET["image"]==="fileIsTooBig")
 {
  echo'<div class="flexCenter">';
  echo' <div class="elementBg">Uploaded file is too big!</div>';
  echo'</div>';
 }
 else if(isset($_GET["image"]) && $_GET["image"]==="profileImageStatusIsNotUpdated")
 {
  echo'<div class="flexCenter">';
  echo' <div class="elementBg">We didn\'t update the status!</div>';
  echo'</div>';
 }
 else if(isset($_GET["image"]) && $_GET["image"]==="uploadSuccess")
 {
  echo'<div class="flexCenter">';
  echo' <div class="elementBg">Image is uploaded successfully!</div>';
  echo'</div>';
 }
 else if(isset($_GET["image"]) && $_GET["image"]==="fileIsNotDeleted")
 {
  echo'<div class="flexCenter">';
  echo' <div class="elementBg">We failed to delete your image!</div>';
  echo'</div>';
 }
 else if(isset($_GET["image"]) && $_GET["image"]==="fileIsDeleted")
 {
  echo'<div class="flexCenter">';
  echo' <div class="elementBg">Image is deleted!</div>';
  echo'</div>';
 }
 else if(isset($_GET["image"]) && $_GET["image"]==="fileWithThatNameDoesNotExist")
 {
  echo'<div class="flexCenter">';
  echo' <div class="elementBg">Image with that name doesn\'t exist!</div>';
  echo'</div>';
 }
 else if(isset($_GET["image"]) && $_GET["image"]==="fileIsDeletedButProfileImageTableIsNotUpdated")
 {
  echo'<div class="flexCenter">';
  echo' <div class="elementBg">Image is deleted, but profile image table is not updated!</div>';
  echo'</div>';
 }
}

function profileInputs()
{
 $countries=getCountries();
 $countryCodes=getCountryCodes();
 $cities=getCities($_SESSION["countryID"]);

 $countryDetails=getCountryDetails($_SESSION["countryID"]);
 $cityDetails=getCityDetails($_SESSION["cityID"]);

 if(!$countryDetails || !is_array($countryDetails))
 {
  $countryNameFD='';
  $countryCodeFD='';
 }
 else
 {
  $countryNameFD=$countryDetails["countryName"];
  $countryCodeFD=$countryDetails["countryCode"];
 }

 if(!$cityDetails || !is_array($cityDetails))
 {
  $cityNameFD='';
  $postalCodeFD='';
 }
 else
 {
  $cityNameFD=$cityDetails["cityName"];
  $postalCodeFD=$cityDetails["postalCode"];
 }

 $phone=$_SESSION["phone"];
 $localNumber=$phone;

 if(strpos($phone,'+'.$countryCodeFD)===0)
  $localNumber=trim(substr($phone,strlen('+'.$countryCodeFD)));

 echo'<div>';
 echo' <label for="Firstname">Firstname:</label>';
 echo' <br>';
 echo' <input type="text" name="firstname" placeholder="Firstname" autocomplete="on" required  id="Firstname" class="input" value="'.$_SESSION["firstname"].'">';
 echo'</div>';
 echo'<br>';

 echo'<div>';
 echo' <label for="LastName">Lastname:</label>';
 echo' <br>';
 echo' <input type="text" name="lastname" placeholder="Lastname" autocomplete="on" required id="LastName" class="input" value="'.$_SESSION["lastname"].'">';
 echo'</div>';
 echo'<br>';
 
 echo'<input type="checkbox" id="BirthdateSwitcher" name="birthdateSwitcher" value="1">';
 echo'<div class="flex" id="BirthdateLabelGroup">';
 echo' <div class="flexLeft" id="BirthdateLabelGroupText">';
 echo'  <label for="BirthdateDate" id="BirthdateDateLabel">Birthdate:</label>';
 echo'  <label for="BirthdateDayText" id="BirthdateDayTextLabel">Day/</label>';
 echo'  <label for="BirthdateMonthText" id="BirthdateMonthTextLabel">Mounth/</label>';
 echo'  <label for="BirthdateYearText" id="BirthdateYearTextLabel">Year</label>';
 echo' </div>';
 echo' <div class="flexRight">';
 echo'  <label for="BirthdateSwitcher"><img src="../../img/svg/switch.svg" width="14px" alt="switch" class="buttonEffect"></label>';
 echo' </div>';
 echo'</div>';
 echo'<div id="BirthdateDateInput">';
 echo' <input type="date" name="birthdate" autocomplete="on" min="1905-01-01" id="BirthdateDate" class="input" value="'.$_SESSION["birthdate"].'">';
 echo'</div>';
 echo'<div id="BirthdateCustomDate">';
 echo' <input type="number" name="day" placeholder="Day" autocomplete="on" min="1" id="BirthdateDayText" class="input" value="'.$_SESSION["day"].'">';
 echo' <input type="number" name="month" placeholder="Month" autocomplete="on" min="1" max="12" id="BirthdateMonthText" class="input" value="'.$_SESSION["month"].'">';
 echo' <input type="number" name="year" placeholder="Year" autocomplete="on" min="1905" id="BirthdateYearText" class="input" value="'.$_SESSION["year"].'">';
 echo'</div>';
 echo'<br>';

 echo'<input type="checkbox" id="CountrySwitcher" name="countrySwitcher" value="1">';
 echo'<div class="flex" id="ContryLabelGroup">';
 echo' <div class="flexLeft" id="ContryLabelGroupText">';
 echo'  <label for="CountrySelect" id="CountrySelectLabel">Country:</label>';
 echo'  <label for="CountryText" id="CountryTextLabel">Country:</label>';
 echo' </div>';
 echo' <div class="flexRight">';
 echo'  <label for="CountrySwitcher"><img src="../../img/svg/switch.svg" width="14px" alt="switch" class="buttonEffect"></label>';
 echo' </div>';
 echo'</div>';
 echo'<div id="CountryInputGroup">';
 echo' <select name="countrySelect" id="CountrySelect" autocomplete="on" class="input select" oninput="getCities(this.value)">';
 echo'  <option value="'.$countryNameFD.'">'.$countryNameFD.'</option><hr>';
 echo'  <option value="Serbia">🇷🇸 Serbia</option>';
 echo'  <option value="Montenegro">🇲🇪 Montenegro</option>';
 echo'  <option value="Croatia">🇭🇷 Croatia</option>';
 echo'  <option value="Bosnia and Herzegovina">🇧🇦 Bosnia and Herzegovina</option><hr>';
        foreach($countries as $country)
        {
 echo'   <option value="'.$country["countryName"].'">'.$country["countryFlag"]." ".$country["countryName"].'</option>';
        }
 echo' </select>';
 echo' <input type="text" name="countryText" placeholder="Country" list="listOfCountries" autocomplete="on" id="CountryText" class="input" oninput="getCities(this.value)" value="'.$countryNameFD.'">';
 echo' <datalist id="listOfCountries">';
       foreach($countries as $country)
       {
 echo'  <option value="'.$country["countryName"].'">'.$country["countryFlag"]." ".$country["countryName"].'</option>';
       }
 echo' </datalist>';
 echo'</div>';
 echo'<br>';
 echo'<div id="ContryCodeGroup">';
 echo' <label for="CountryCodeText" id="CountryCodeLabel">Country code:</label>';
 echo' <input type="text" name="countryCode" placeholder="Country code" list="listOfCountryCodes" autocomplete="on" id="CountryCodeText" class="input" value="'.$countryCodeFD.'">';
 echo' <datalist id="listOfCountryCodes">';
       foreach($countryCodes as $countryCode)
       {
 echo'  <option value="+'.$countryCode["countryCode"].'">'.$countryCode["countryFlag"].' +'.$countryCode["countryCode"].'</option>';
       }
 echo' </datalist>';
 echo'</div>';
 echo'<input type="checkbox" id="CitySwitcher" name="citySwitcher" value="1">';
 echo'<div class="flex" id="CityLabelGroup">';
 echo' <div class="flexLeft" id="CityLabelGroupText">';
 echo'  <label for="CitySelect" id="CitySelectLabel">City:</label>';
 echo'  <label for="CityText" id="CityTextLabel">City:</label>';
 echo' </div>';
 echo' <div class="flexRight" id="CitySwitcherLabelDiv">';
 echo'   <label for="CitySwitcher" id="CitySwitcherLabel"><img src="../../img/svg/switch.svg" width="14px" alt="switch" class="buttonEffect"></label>';
 echo' </div>';
 echo'</div>';
 echo'<div id="CityInputGroup">';
 echo' <select name="citySelect" id="CitySelect" autocomplete="on" class="input select">';
 echo'  <option value="'.$cityNameFD.'">'.$cityNameFD.'</option>';
       foreach($cities as $city)
       {
 echo'  <option value="'.$city["cityName"].'">'.$city["cityName"].'</option>';
       }
 echo' </select>';
 echo' <input type="text" name="cityText" placeholder="City" autocomplete="on" list="listOfCities" id="CityText" class="input" oninput="getPostalCode(this.value)" value="'.$cityNameFD.'">';
 echo' <datalist id="listOfCities">';
 echo' </datalist>';
 echo'</div>';
 echo'<br>';
 echo'<div id="PostalCodeGroup">';
 echo' <label for="PostalCodeText" id="PostalCodeLabel">Postal code:</label>';
 echo' <input type="text" name="postalCode" placeholder="Postal code" list="listOfPostalCodes" autocomplete="on" id="PostalCodeText" class="input" value="'.$postalCodeFD.'">';
 echo' <p class="small" id="GuideTextForPostalCode">*If you don\'t know put "none"*</p>';
 echo' <datalist id="listOfPostalCodes">';
 echo' </datalist>';
 echo'</div>';

 echo'<div>';
 echo' <label for="Address">Address:</label>';
 echo' <br>';
 echo' <input type="text" name="address" placeholder="Address" required autocomplete="on" id="Address" class="input" value="'.$_SESSION["address"].'">';
 echo'</div>';

 echo'<br>';

 echo'<input type="checkbox" id="PhoneSwitcher" name="phoneSwitcher" value="1">';
 echo'<div class="flex">';
 echo' <div class="flexLeft">';
 echo'  <label for="PhoneTextPart">Phone:</label>';
 echo' </div>';
 echo' <div class="flexRight">';
 echo'  <label for="PhoneSwitcher"><img src="../../img/svg/switch.svg" width="14px" alt="switch" class="buttonEffect"></label>';
 echo' </div>';
 echo'</div>';
 echo'<div class="flex" id="PhoneInputGroup">';
 echo' <select name="phoneCountryCode" id="PhoneCountryCodePart" class="input select">';
 echo'  <option value="+'.$countryCodeFD.'">+'.$countryCodeFD.'</option><hr>';
 echo'  <option value="+381">🇷🇸 +381</option>';
 echo'  <option value="+382">🇲🇪 +382</option>';
 echo'  <option value="+385">🇭🇷 +385</option>';
 echo'  <option value="+387">🇧🇦 +387</option><hr>';
        foreach($countryCodes as $countryCode)
        {
 echo'   <option value="+'.$countryCode["countryCode"].'">'.$countryCode["countryFlag"].' +'.$countryCode["countryCode"].'</option>';
        }
 echo' </select>';
 echo' <input type="text" name="phone" placeholder="Phone" required autocomplete="on" id="PhoneTextPart" class="input" data-full-phone="'.$phone.'" data-local-phone="'.$localNumber.'" value="'.$localNumber.'">';
 echo'</div>';
 echo'<script>';
 echo'document.addEventListener("DOMContentLoaded",function()';
 echo'{';
 echo' const phoneSwitcher=document.getElementById("PhoneSwitcher");';
 echo' const phoneInput=document.getElementById("PhoneTextPart");';

 echo' function updatePhoneValue()';
 echo' {';
 echo'  if(phoneSwitcher.checked)';
 echo'  {';
 echo'   phoneInput.value=phoneInput.dataset.fullPhone;';
 echo'  }';
 echo'  else';
 echo'  {';
 echo'   phoneInput.value=phoneInput.dataset.localPhone;';
 echo'  }';
 echo' }';
 echo' updatePhoneValue();';
 echo' phoneSwitcher.addEventListener("change",updatePhoneValue);';
 echo'});';
 echo'</script>';
 echo'<br>';
}
?>
