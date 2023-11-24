var map = L.map("map").fitBounds([
  [42, -5],
  [51, 10],
]);

L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
    maxZoom: 19,
    attribution:
    '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
}).addTo(map);

var redIcon = L.icon({
    iconUrl: "./red-marker-icon.png",
});

var markerLayer = L.layerGroup();
L.marker([42, -5], { icon: redIcon })
  .bindPopup("Bottom Left")
  .addTo(markerLayer);
L.marker([51, 10], { icon: redIcon }).addTo(markerLayer);
L.marker([48.86, 2.35], { icon: redIcon }).addTo(markerLayer);
L.marker([45.73, 4.84], { icon: redIcon }).addTo(markerLayer);

var secondMarkerLayer = L.layerGroup();
L.marker([47.22, -1.54]).addTo(secondMarkerLayer);
L.marker([45.04134, 3.8799]).addTo(secondMarkerLayer);
L.marker([45.04308, 3.88361]).addTo(secondMarkerLayer);

var layerGroups = {
    First: markerLayer,
    Second: secondMarkerLayer,
};
var layerControl = L.control.layers(null, layerGroups).addTo(map);
