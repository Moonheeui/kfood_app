console.info('js/map.js loaded');

let map;

async function initMap() {
  const { Map } = await google.maps.importLibrary("maps");
  let koreanFood = { lat: 45.4957591, lng: -73.5753508 };
  // let koreanFood2 = { lat: 45.4954269, lng: -73.865559 };
  // let koreanFood3 = {lat: 45.4760519, lng: -73.6162619};

  
  map = new Map(document.querySelector("#map"), {
    center: koreanFood,   /* koreanFood2, koreanFood3, */
    zoom: 15,
  });

  let marker = new google.maps.Marker({
    position: koreanFood,
    map: map,
    title: 'Korean Food',
    label: {text: '🍲', fontsize: "5rem"}
  });
}