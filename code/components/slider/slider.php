    <?php
     if(!isset($numberOfSliders))
     {
      $numberOfSliders=1;
     }
     if(!isset($numberOfSlides))
     {
      $numberOfSlides=1;
     }
     $sliderNumber=1;
     $slideNumber=1;
     $numberOfSliders=1;
     $numberOfSlides=10;
     $cssFile="../components/slider/slider.css";
     $css="";
     $imagePath="../../img/sliderImages/";
     $imageName="valensija";
     $imageExtention=".jpg";

     if(!file_exists($cssFile))
     {
      exit('Error: '.$cssFile.' file is not found');
     }
     $css.='@media screen
{
 .slider
 {
  display:flex;
  flex-direction:column;
  justify-content:center;
  max-width:55%;
  margin:1.2rem auto;
  min-width:182px;
 }
 .slider input[type=radio]
 {
  display:none;
 }
 .slider label
 {
  cursor:pointer;
  text-decoration:none;
 }
 .imageLink
 {
  background-repeat:no-repeat;
  background-size:cover;
 }
';
     for($sliderNumber=1;$sliderNumber<=$numberOfSliders;++$sliderNumber)
     {
      $css.=' #sliderImages'.$sliderNumber.'
 {
  aspect-ratio:16/9;
  display:flex;
  overflow-x:hidden;
  scroll-snap-type:x mandatory;
  scroll-behavior:smooth;
  border:0.12rem solid var(--backgroundColor);
  border-radius:var(--borderRadius);
 }
 #sliderImages'.$sliderNumber.' img
 {
  flex:1 0 100%;
  scroll-snap-align:start;
  object-fit:cover;
 }
';
      for($slideNumber=1;$slideNumber<=$numberOfSlides;++$slideNumber)
      {
       $slide=$slideNumber;
       $css.=' #slider'.$sliderNumber.'Slide'.$slideNumber.'Nav:checked ~ #sliderImages'.$sliderNumber.' #slider'.$sliderNumber.'Slide'.$slideNumber.'
 {
  margin-left:'.(0-(--$slide)*100).'%;
 }
';
      }
      $css.=' #controls'.$sliderNumber.' label
 {
  display:none;
  width:2.2rem;
  height:2.2rem;
 }
';
      for($slideNumber=1;$slideNumber<=$numberOfSlides-1;++$slideNumber)
      {
       $slide=$slideNumber;
       $css.=' #slider'.$sliderNumber.'Slide'.$slideNumber.'Nav:checked ~ .navigation #controls'.$sliderNumber.' label:nth-child('.(++$slide).'),
';
      }
      $css.=' #slider'.$sliderNumber.'Slide'.$numberOfSlides.'Nav:checked ~ .navigation #controls'.$sliderNumber.' label:nth-child(1)
';
      $css.=' {
  background:url("../'.$imagePath.'../svg/rightArrow.svg");
  background-repeat:no-repeat;
  background-size:cover;
  background-color:var(--color);
  border:0.12rem solid var(--backgroundColor);
  border-radius:25rem;
  margin:0.2rem;
  float:right;
  display:block;
 }
';
      $css.=' #slider'.$sliderNumber.'Slide1Nav:checked ~ .navigation #controls'.$sliderNumber.' label:nth-last-child(1),
';
      $slide=0;
      for($slideNumber=2;$slideNumber<=$numberOfSlides-1;++$slideNumber)
      {
       $css.=' #slider'.$sliderNumber.'Slide'.$slideNumber.'Nav:checked ~ .navigation #controls'.$sliderNumber.' label:nth-last-child('.($numberOfSlides-$slide).'),
';
       ++$slide;
      }
      $css.=' #slider'.$sliderNumber.'Slide'.$numberOfSlides.'Nav:checked ~ .navigation #controls'.$sliderNumber.' label:nth-last-child(2)
 {
  background:url("../'.$imagePath.'../svg/leftArrow.svg");
  background-repeat:no-repeat;
  background-size:cover;
  background-color:var(--color);
  border:0.12rem solid var(--backgroundColor);
  border-radius:25rem;
  margin:0.2rem;
  float:left;
  display:block;
 }
 #bullets'.$sliderNumber.'
 {
  margin:0.2rem;
  padding:0.2rem;
  display:flex;
  flex-wrap:wrap;
  justify-content:center;
  align-items:center;
  background-color:var(--accentColor);
  border:0.12rem solid var(--backgroundColor);
  border-radius:var(--borderRadius);
 }
 #bullets'.$sliderNumber.' label
 {
  flex:1 0 1.5rem;
  object-fit:cover;
  margin:0.1rem;
  max-width:1.5rem;
  height:1.5rem;
  background-color:var(--backgroundColor);
  border:0.1rem solid var(--backgroundColor);
  border-radius:var(--borderRadius);
  opacity:0.65;
  cursor:pointer;
 }
 #bullets'.$sliderNumber.' label:hover
 {
  opacity:1;
 }
';
      for($slideNumber=1;$slideNumber<=$numberOfSlides-1;++$slideNumber)
      {
       $css.=' #slider'.$sliderNumber.'Slide'.$slideNumber.'Nav:checked ~ .navigation #bullets'.$sliderNumber.' label:nth-child('.$slideNumber.'),
';
      }
      $css.=' #slider'.$sliderNumber.'Slide'.$numberOfSlides.'Nav:checked ~ .navigation #bullets'.$sliderNumber.' label:nth-child('.$numberOfSlides.')
';
      $css.='
 {
  opacity:1;
 }
}
';
      $screenWidth=[1399,1200,992,768,580];
      $sliderWidth=[65,70,80,90,100];
      for($i=0;$i<5;++$i)
      {
       $css.='@media screen and (max-width:'.$screenWidth[$i].'px)
{
 .slider
 {
  max-width:'.$sliderWidth[$i].'%;
 }
}
';
      }
     }
     if(file_put_contents($cssFile, $css)===false)
     {
      exit('Error: Unable to write to '.$cssFile);
     }
    ?>
    <?php
     for($sliderNumber=1;$sliderNumber<=$numberOfSliders;++$sliderNumber)
     {
      $slideNumber=1;
      echo'<div class="slider">';
      echo'<input type="radio" name="slider'.$sliderNumber.'" id="'.'slider'.$sliderNumber.'Slide'.$slideNumber.'Nav'.'" checked>';
      for($slideNumber=2;$slideNumber<=$numberOfSlides;++$slideNumber)
      {
       echo'<input type="radio" name="slider'.$sliderNumber.'" id="'.'slider'.$sliderNumber.'Slide'.$slideNumber.'Nav'.'">';
      }
      echo'<div id="sliderImages'.$sliderNumber.'">';
      for($slideNumber=1;$slideNumber<=$numberOfSlides;++$slideNumber)
      {
       echo'<img src="'.$imagePath.$imageName.$slideNumber.$imageExtention.'" alt="'.$imageName.' '.$slideNumber.'" id="'.'slider'.$sliderNumber.'Slide'.$slideNumber.'">';
      }
      echo'</div>';
      echo'<div class="navigation">';
      echo'<div id="controls'.$sliderNumber.'">';
      for($slideNumber=1;$slideNumber<=$numberOfSlides;++$slideNumber)
      {
       echo' <label for="'.'slider'.$sliderNumber.'Slide'.$slideNumber.'Nav'.'"></label>';
      }
      echo'</div>';
      echo'<div id="bullets'.$sliderNumber.'">';
      for($slideNumber=1;$slideNumber<=$numberOfSlides;++$slideNumber)
      {
       echo' <label for="'.'slider'.$sliderNumber.'Slide'.$slideNumber.'Nav'.'" class="imageLink" style="background-image:url(\''.$imagePath.$imageName.$slideNumber.$imageExtention.'\'); id='.'slider'.$sliderNumber.'Bullet'.$slideNumber.'"></label>';
      }
      echo'</div>';
      echo'</div>';
      echo'</div>';
     }
    ?>
