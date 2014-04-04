$(document).ready(function () {
   $('img').hover(
       function()
       {
          $('#auth').animate({"opacity": 1},100); 
       },
       function()
       {
          $('#auth').animate({"opacity": 0.8},100);
       }
          
   );
});