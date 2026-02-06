function getCities(countryName)
{
 let citiesSelect=document.getElementById("CitySelect");
 let cityText=document.getElementById("CityText");
 let listOfCities=document.getElementById("listOfCities");
 let countryCodeText=document.getElementById("CountryCodeText");
 let phoneCountryCode=document.getElementById("PhoneCountryCodePart");

 if(countryName.trim()==="")
 {
  citiesSelect.disabled=true;
  citiesSelect.selectedIndex=0;
  return false;
 }

 fetch(`../components/signUp/dinamicSelect.php?countryName=${encodeURIComponent(countryName)}`)
 .then(response=>response.json())
 .then(function(data)
 {
  let cities=data.cities;
  let countryCode=data.countryCode;
  let out='';
  for(let city of cities)
  {
   out+=`<option value="${city['cityName']}">${city['cityName']}</option>`;
  }
  citiesSelect.innerHTML=out;
  listOfCities.innerHTML=out;
  citiesSelect.disabled=false;

  if(countryCode)
  {
   countryCodeText.value=countryCode;
   for(let option of phoneCountryCode.options)
   {
    if(option.value===countryCode)
    {
     phoneCountryCode.value=countryCode;
     break;
    }
   }
  }
 });
}
function getPostalCode(cityName)
{
 let postalCodeInput=document.getElementById("PostalCodeText");

 if(cityName.trim()==="")
 {
  postalCodeInput.value = "";
  return;
 }

 fetch(`../components/signUp/dinamicSelect.php?cityName=${encodeURIComponent(cityName)}`)
 .then(response=>response.json())
 .then(function(data)
 {
  if(data.postalCode)
  {
   postalCodeInput.value=data.postalCode;
  }
 });
}
