<?php

require_once __DIR__.'/../default/db.php';
require __DIR__.'/../signUp/dinamicSelect.php';
require_once __DIR__.'/../default/session.php';

function checkSignupErrors()
{
 if(isset($_SESSION["errorsAtSignup"]))
 {
  $errors=$_SESSION['errorsAtSignup'];

  foreach($errors as $error)
  {
   echo'<div class="flexCenter">';
   echo' <p class="elementBg">'.$error.'</p>';
   echo'</div>';
  }
  unset($_SESSION['errorsAtSignup']);
 }
 else if(isset($_GET["signup"]) && $_GET["signup"]==="failedToSendAEmail")
 {
  echo'<div class="flexCenter">';
  echo' <div class="bg">We failed to send you a email!</div>';
  echo'</div>';
 }
 else if(isset($_GET["signup"]) && $_GET["signup"]==="pending")
 {
  echo'<div class="flexCenter">';
  echo' <div class="bg">Please check your emails, because we send you a activation link!</div>';
  echo'</div>';
 }
 else if(isset($_GET["signup"]) && $_GET["signup"]==="failedToCreateANewUser")
 {
  echo'<div class="flexCenter">';
  echo' <div class="bg">Your signup failed!</div>';
  echo'</div>';
 }
 else if(isset($_GET["signup"]) && $_GET["signup"]==="emailIsDeleted")
 {
  echo'<div class="flexCenter">';
  echo' <div class="bg">Your profile is deleted!</div>';
  echo'</div>';
 }
 else if(isset($_GET["signup"]) && $_GET["signup"]==="alreadyVerified")
 {
  echo'<div class="flexCenter">';
  echo' <div class="bg">Your profile is already activated!</div>';
  echo'</div>';
 }
 else if(isset($_GET["signup"]) && $_GET["signup"]==="statusUpdateFailed")
 {
  echo'<div class="flexCenter">';
  echo' <div class="bg">We failed to change a status!</div>';
  echo'</div>';
 }
 else if(isset($_GET["signup"]) && $_GET["signup"]==="failed")
 {
  echo'<div class="flexCenter">';
  echo' <div class="bg">Your profile activation failed!</div>';
  echo'</div>';
 }
 else if(isset($_GET["signup"]) && $_GET["signup"]==="failedToChangeToken")
 {
  echo'<div class="flexCenter">';
  echo' <div class="bg">We failed to change a token!</div>';
  echo'</div>';
 }
 else if(isset($_GET["signup"]) && $_GET["signup"]==="success")
 {
  echo'<div class="flexCenter">';
  echo' <div class="bg white">Your profile activated, you can now log in!</div>';
  echo'</div>';
 }
}

function signupInputs()
{
 $countries=getCountries();
 $countryCodes=getCountryCodes();

 if(isset($_SESSION["signupData"]["firstname"]) && !isset($_SESSION["errorsAtSignup"]["invalidFirstName"]) && !(isset($_GET["signup"]) && $_GET["signup"]==="success"))
 {
  echo'<div>';
  echo' <label for="Firstname">Firstname:</label>';
  echo' <br>';
  echo' <input type="text" name="firstname" placeholder="Firstname" autocomplete="on" required  id="Firstname" class="input" value="'.$_SESSION["signupData"]["firstname"].'">';
  echo'</div>';
 }
 else
 {
  echo'<div>';
  echo' <label for="Firstname">Firstname:</label>';
  echo' <br>';
  echo' <input type="text" name="firstname" placeholder="Firstname" autocomplete="on" required  id="Firstname" class="input">';
  echo'</div>';
 }
 echo'<br>';

 if(isset($_SESSION["signupData"]["lastname"]) && !isset($_SESSION["errorsAtSignup"]["invalidLastName"]) && !(isset($_GET["signup"]) && $_GET["signup"]==="success"))
 {
  echo'<div>';
  echo' <label for="LastName">Lastname:</label>';
  echo' <br>';
  echo' <input type="text" name="lastname" placeholder="Lastname" autocomplete="on" required id="LastName" class="input" value="'.$_SESSION["signupData"]["lastname"].'">';
  echo'</div>';
 }
 else
 {
  echo'<div>';
  echo' <label for="LastName">Lastname:</label>';
  echo' <br>';
  echo' <input type="text" name="lastname" placeholder="Lastname" autocomplete="on" required id="LastName" class="input">';
  echo'</div>';
 }
 echo'<br>';
 
 if((isset($_SESSION["signupData"]["birthdate"]) || (isset($_SESSION["signupData"]["day"]) && isset($_SESSION["signupData"]["month"]) && isset($_SESSION["signupData"]["year"]))) && (!isset($_SESSION["errorsAtSignup"]["invalidBirthdate"]) && !isset($_SESSION["errorsAtSignup"]["invalidBirthdateParts"])) && !(isset($_GET["signup"]) && $_GET["signup"]==="success"))
 {
  if(isset($_SESSION["signupData"]["birthdateSwitcher"]))
  {
   echo'<input type="checkbox" id="BirthdateSwitcher" name="birthdateSwitcher" value="1" checked>';
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
   echo' <input type="date" name="birthdate" autocomplete="on" min="1905-01-01" id="BirthdateDate" class="input">';
   echo'</div>';
   echo'<div id="BirthdateCustomDate">';
   echo' <input type="number" name="day" placeholder="Day" autocomplete="on" min="1" id="BirthdateDayText" class="input" value="'.$_SESSION["signupData"]["day"].'">';
   echo' <input type="number" name="month" placeholder="Month" autocomplete="on" min="1" max="12" id="BirthdateMonthText" class="input" value="'.$_SESSION["signupData"]["month"].'">';
   echo' <input type="number" name="year" placeholder="Year" autocomplete="on" min="1905" id="BirthdateYearText" class="input" value="'.$_SESSION["signupData"]["year"].'">';
   echo'</div>';
  }
  else
  {
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
   echo' <input type="date" name="birthdate" autocomplete="on" min="1905-01-01" id="BirthdateDate" class="input" value="'.$_SESSION["signupData"]["birthdate"].'">';
   echo'</div>';
   echo'<div id="BirthdateCustomDate">';
   echo' <input type="number" name="day" placeholder="Day" autocomplete="on" min="1" id="BirthdateDayText" class="input">';
   echo' <input type="number" name="month" placeholder="Month" autocomplete="on" min="1" max="12" id="BirthdateMonthText" class="input">';
   echo' <input type="number" name="year" placeholder="Year" autocomplete="on" min="1905" id="BirthdateYearText" class="input">';
   echo'</div>';
  }
 }
 else
 {
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
  echo' <input type="date" name="birthdate" autocomplete="on" min="1905-01-01" id="BirthdateDate" class="input">';
  echo'</div>';
  echo'<div id="BirthdateCustomDate">';
  echo' <input type="number" name="day" placeholder="Day" autocomplete="on" min="1" id="BirthdateDayText" class="input">';
  echo' <input type="number" name="month" placeholder="Month" autocomplete="on" min="1" max="12" id="BirthdateMonthText" class="input">';
  echo' <input type="number" name="year" placeholder="Year" autocomplete="on" min="1905" id="BirthdateYearText" class="input">';
  echo'</div>';
 }
 echo'<br>';
 
 if((isset($_SESSION["signupData"]["country"]) && isset($_SESSION["signupData"]["city"])) && !isset($_SESSION["errorsAtSignup"]["invalidCountry"]) && !isset($_SESSION["errorsAtSignup"]["invalidCity"]) && !(isset($_GET["signup"]) && $_GET["signup"]==="success"))
 {
  if(isset($_SESSION["signupData"]["countrySwitcher"]))
  {
   echo'<input type="checkbox" id="CountrySwitcher" name="countrySwitcher" value="1" checked>';
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
   echo'  <option value="">Country</option><hr>';
   echo'  <option value="Serbia">🇷🇸 Serbia</option>';
   echo'  <option value="Montenegro">🇲🇪 Montenegro</option>';
   echo'  <option value="Croatia">🇭🇷 Croatia</option>';
   echo'  <option value="Bosnia and Herzegovina">🇧🇦 Bosnia and Herzegovina</option><hr>';
          foreach($countries as $country)
          {
   echo'   <option value="'.$country["countryName"].'">'.$country["countryFlag"]." ".$country["countryName"].'</option>';
          }
   echo' </select>';
   echo' <input type="text" name="countryText" placeholder="Country" list="listOfCountries" autocomplete="on" id="CountryText" class="input" oninput="getCities(this.value)" value="'.$_SESSION["signupData"]["country"].'">';
   echo' <datalist id="listOfCountries">';
          foreach($countries as $country)
          {
   echo'   <option value="'.$country["countryName"].'">'.$country["countryFlag"]." ".$country["countryName"].'</option>';
          }
   echo' </datalist>';
   echo'</div>';
   echo'<br>';
   echo'<div id="ContryCodeGroup">';
   echo' <label for="CountryCodeText" id="CountryCodeLabel">Country code:</label>';
   echo' <input type="text" name="countryCode" placeholder="Country code" list="listOfCountryCodes" autocomplete="on" id="CountryCodeText" class="input" value="'.$_SESSION["signupData"]["countryCode"].'">';
   echo' <p class="small" id="GuideTextForCountryCode">*If you don\'t know put "none"*</p>';
   echo' <datalist id="listOfCountryCodes">';
          foreach($countryCodes as $countryCode)
          {
   echo'   <option value="+'.$countryCode["countryCode"].'">'.$countryCode["countryFlag"].' +'.$countryCode["countryCode"].'</option>';
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
   echo' <select name="citySelect" id="CitySelect" autocomplete="on" class="input select" disabled>';
   echo'  <option value="">City</option>;';
   echo' </select>';
   echo' <input type="text" name="cityText" placeholder="City" autocomplete="on" list="listOfCities" id="CityText" class="input" oninput="getPostalCode(this.value)" value="'.$_SESSION["signupData"]["city"].'">';
   echo' <datalist id="listOfCities">';
   echo' </datalist>';
   echo'</div>';
   echo'<br>';
   echo'<div id="PostalCodeGroup">';
   echo' <label for="PostalCodeText" id="PostalCodeLabel">Postal code:</label>';
   echo' <input type="text" name="postalCode" placeholder="Postal code" list="listOfPostalCodes" autocomplete="on" id="PostalCodeText" class="input" value="'.$_SESSION["signupData"]["postalCode"].'">';
   echo' <p class="small" id="GuideTextForPostalCode">*If you don\'t know put "none"*</p>';
   echo' <datalist id="listOfPostalCodes">';
   echo' </datalist>';
   echo'</div>';
  }
  else if(isset($_SESSION["signupData"]["citySwitcher"]))
  {
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
   echo'  <option value="">Country</option><hr>';
   echo'  <option value="Serbia">🇷🇸 Serbia</option>';
   echo'  <option value="Montenegro">🇲🇪 Montenegro</option>';
   echo'  <option value="Croatia">🇭🇷 Croatia</option>';
   echo'  <option value="Bosnia and Herzegovina">🇧🇦 Bosnia and Herzegovina</option><hr>';
          foreach($countries as $country)
          {
   echo'   <option value="'.$country["countryName"].'">'.$country["countryFlag"]." ".$country["countryName"].'</option>';
          }
   echo' </select>';
   echo' <input type="text" name="countryText" placeholder="Country" list="listOfCountries" autocomplete="on" id="CountryText" class="input" oninput="getCities(this.value)">';
   echo' <datalist id="listOfCountries">';
          foreach($countries as $country)
          {
   echo'   <option value="'.$country["countryName"].'">'.$country["countryFlag"]." ".$country["countryName"].'</option>';
          }
   echo' </datalist>';
   echo'</div>';
   echo'<br>';
   echo'<div id="ContryCodeGroup">';
   echo' <label for="CountryCodeText" id="CountryCodeLabel">Country code:</label>';
   echo' <input type="text" name="countryCode" placeholder="Country code" list="listOfCountryCodes" autocomplete="on" id="CountryCodeText" class="input">';
   echo' <datalist id="listOfCountryCodes">';
          foreach($countryCodes as $countryCode)
          {
   echo'   <option value="+'.$countryCode["countryCode"].'">'.$countryCode["countryFlag"].' +'.$countryCode["countryCode"].'</option>';
          }
   echo' </datalist>';
   echo'</div>';
   echo'<input type="checkbox" id="CitySwitcher" name="citySwitcher" value="1" checked>';
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
   echo' <select name="citySelect" id="CitySelect" autocomplete="on" class="input select" disabled>';
   echo'  <option value="">City</option>;';
   echo' </select>';
   echo' <input type="text" name="cityText" placeholder="City" autocomplete="on" list="listOfCities" id="CityText" class="input" oninput="getPostalCode(this.value)"  value="'.$_SESSION["signupData"]["city"].'">';
   echo' <datalist id="listOfCities">';
   echo' </datalist>';
   echo'</div>';
   echo'<br>';
   echo'<div id="PostalCodeGroup">';
   echo' <label for="PostalCodeText" id="PostalCodeLabel">Postal code:</label>';
   echo' <input type="text" name="postalCode" placeholder="Postal code" list="listOfPostalCodes" autocomplete="on" id="PostalCodeText" class="input" value="'.$_SESSION["signupData"]["postalCode"].'">';
   echo' <p class="small" id="GuideTextForPostalCode">*If you don\'t know put "none"*</p>';
   echo' <datalist id="listOfPostalCodes">';
   echo' </datalist>';
   echo'</div>';
  }
 }
 else
 {
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
  echo'  <option value="">Country</option><hr>';
  echo'  <option value="Serbia">🇷🇸 Serbia</option>';
  echo'  <option value="Montenegro">🇲🇪 Montenegro</option>';
  echo'  <option value="Croatia">🇭🇷 Croatia</option>';
  echo'  <option value="Bosnia and Herzegovina">🇧🇦 Bosnia and Herzegovina</option><hr>';
         foreach($countries as $country)
         {
  echo'   <option value="'.$country["countryName"].'">'.$country["countryFlag"]." ".$country["countryName"].'</option>';
         }
  echo' </select>';
  echo' <input type="text" name="countryText" placeholder="Country" list="listOfCountries" autocomplete="on" id="CountryText" class="input" oninput="getCities(this.value)">';
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
  echo' <input type="text" name="countryCode" placeholder="Country code" list="listOfCountryCodes" autocomplete="on" id="CountryCodeText" class="input">';
  echo' <p class="small" id="GuideTextForCountryCode">*If you don\'t know put "none"*</p>';
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
  echo' <select name="citySelect" id="CitySelect" autocomplete="on" class="input select" disabled>';
  echo'  <option value="">City</option>;';
  echo' </select>';
  echo' <input type="text" name="cityText" placeholder="City" autocomplete="on" list="listOfCities" id="CityText" class="input" oninput="getPostalCode(this.value)">';
  echo' <datalist id="listOfCities">';
  echo' </datalist>';
  echo'</div>';
  echo'<br>';
  echo'<div id="PostalCodeGroup">';
  echo' <label for="PostalCodeText" id="PostalCodeLabel">Postal code:</label>';
  echo' <input type="text" name="postalCode" placeholder="Postal code" list="listOfPostalCodes" autocomplete="on" id="PostalCodeText" class="input">';
  echo' <p class="small" id="GuideTextForPostalCode">*If you don\'t know put "none"*</p>';
  echo' <datalist id="listOfPostalCodes">';
  echo' </datalist>';
  echo'</div>';
 }
 
 if(isset($_SESSION["signupData"]["address"]) && !isset($_SESSION["errorsAtSignup"]["invalidAddress"]) && !(isset($_GET["signup"]) && $_GET["signup"]==="success"))
 {
  echo'<div>';
  echo' <label for="Address">Address:</label>';
  echo' <br>';
  echo' <input type="text" name="address" placeholder="Address" required autocomplete="on" id="Address" class="input" value="'.$_SESSION["signupData"]["address"].'">';
  echo'</div>';
 }
 else
 {
  echo'<div>';
  echo' <label for="Address">Address:</label>';
  echo' <br>';
  echo' <input type="text" name="address" placeholder="Address" required autocomplete="on" id="Address" class="input">';
  echo'</div>';
 }
  echo'<br>';
 
 if(isset($_SESSION["signupData"]["phone"]) && !isset($_SESSION["errorsAtSignup"]["invalidPhone"]) && !(isset($_GET["signup"]) && $_GET["signup"]==="success"))
 {
  if(isset($_SESSION["signupData"]["phoneSwitcher"]))
  {
   echo'<input type="checkbox" id="PhoneSwitcher" name="phoneSwitcher" value="1" checked>';
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
   echo'  <option value="">Choose</option><hr>';
   echo'  <option value="+381">🇷🇸 +381</option>';
   echo'  <option value="+382">🇲🇪 +382</option>';
   echo'  <option value="+385">🇭🇷 +385</option>';
   echo'  <option value="+387">🇧🇦 +387</option><hr>';
          foreach($countryCodes as $countryCode)
          {
   echo'   <option value="+'.$countryCode["countryCode"].'">'.$countryCode["countryFlag"].' +'.$countryCode["countryCode"].'</option>';
          }
   echo' </select>';
   echo' <input type="text" name="phone" placeholder="Phone" required autocomplete="on" id="PhoneTextPart" class="input" value="'.$_SESSION["signupData"]["phone"].'">';
   echo'</div>';
  }
  else
  {
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
   echo'  <option value="">Choose</option><hr>';
   echo'  <option value="+381">🇷🇸 +381</option>';
   echo'  <option value="+382">🇲🇪 +382</option>';
   echo'  <option value="+385">🇭🇷 +385</option>';
   echo'  <option value="+387">🇧🇦 +387</option><hr>';
          foreach($countryCodes as $countryCode)
          {
   echo'   <option value="+'.$countryCode["countryCode"].'">'.$countryCode["countryFlag"].' +'.$countryCode["countryCode"].'</option>';
          }
   echo' </select>';
   echo' <input type="text" name="phone" placeholder="Phone" required autocomplete="on" id="PhoneTextPart" class="input" value="'.$_SESSION["signupData"]["phone"].'">';
   echo'</div>';
  }
 }
 else
 {
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
  echo'  <option value="">Choose</option><hr>';
  echo'  <option value="+381">🇷🇸 +381</option>';
  echo'  <option value="+382">🇲🇪 +382</option>';
  echo'  <option value="+385">🇭🇷 +385</option>';
  echo'  <option value="+387">🇧🇦 +387</option><hr>';
         foreach($countryCodes as $countryCode)
         {
  echo'   <option value="+'.$countryCode["countryCode"].'">'.$countryCode["countryFlag"].' +'.$countryCode["countryCode"].'</option>';
         }
  echo' </select>';
  echo' <input type="text" name="phone" placeholder="Phone" required autocomplete="on" id="PhoneTextPart" class="input">';
  echo'</div>';
 }
  echo'<br>';
 
 if(isset($_SESSION["signupData"]["email"]) && !isset($_SESSION["errorsAtSignup"]["invalidEmail"]) && !isset($_SESSION["errorsAtSignup"]["emailTaken"]) && !(isset($_GET["signup"]) && $_GET["signup"]==="success"))
 {
  echo'<div>';
  echo' <label for="Email">Email:</label>';
  echo' <br>';
  echo' <input type="email" name="email" placeholder="Email" required id="Email" autocomplete="on" class="input" value="'.$_SESSION["signupData"]["email"].'">';
  echo'</div>';
 }
 else
 {
  echo'<div>';
  echo' <label for="Email">Email:</label>';
  echo' <br>';
  echo' <input type="email" name="email" placeholder="Email" required id="Email" autocomplete="on" class="input">';
  echo'</div>';
 }
  echo'<br>';
  echo'<script defer>';
  echo'document.addEventListener("DOMContentLoaded",function()';
  echo'{';
  echo' let yearInput=document.getElementById("BirthdateYearText");';
  echo' yearInput.addEventListener("input", () =>';
  echo' {';
  echo'  if(yearInput.value==="1905" && !yearInput._touched)';
  echo'  {';
  echo'   yearInput.value=2000;';
  echo'  }';
  echo'  yearInput._touched=true;';
  echo' });';
  echo' yearInput.addEventListener("keydown",(e)=>';
  echo' {';
  echo'  if((e.key==="ArrowUp" || e.key==="ArrowDown") && yearInput.value==="")';
  echo'  {';
  echo'   yearInput.value=2000;';
  echo'  }';
  echo' });';
  echo'});';
  echo'</script>';
}

?>
