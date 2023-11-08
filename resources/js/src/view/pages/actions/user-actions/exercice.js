function countTime(leds) {
  
    if(leds.includes(0) == true){
      let count = 0;
      let lan = false;
      while(leds.includes(0) == true){
        
        for(let i=0; i > leds.length; i++){
          if(leds[0] == 0 && leds[leds.length - 1] == 1){
            leds[0]=1;
            lan = true;
          }
          if(leds[i] == 0 && leds[i - 1]== 1){
            leds[i]=1;
            lan = true;
          }
          if(lan == true){
            count = count +1;
          }
  
        }
      }
     return count
    }
  
  }