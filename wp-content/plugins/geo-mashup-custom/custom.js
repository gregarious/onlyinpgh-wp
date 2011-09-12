function customGeoMashupColorIcon ( properties, color_name ) {
  var icon = null;

        // Make an icon for the color 'lime' from images in the geo-mashup/
images directory

  if (color_name == 'aqua') {
    icon = new GIcon();
    icon.image = '/wp-content/uploads/2011/08/mm_20_arts.png';
    icon.shadow = '/wp-content/uploads/2011/08/shadow.png';
    icon.iconSize = new GSize(20, 20);
    icon.shadowSize = new GSize(20, 34);
    icon.iconAnchor = new GPoint(8, 20);
    icon.infoWindowAnchor = new GPoint(8, 1);
  }

  return icon;

} 