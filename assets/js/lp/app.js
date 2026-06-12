/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.scss in this case)
// require('../css/app.scss');
const imagesContext = require.context(
  "../../images/lp",
  true,
  /\.(png|jpg|jpeg|gif|ico|svg|webp)$/
);
imagesContext.keys().forEach(imagesContext);
// require.context('../scss', false, /\.scss$|.sass$/);
// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
const $ = require("jquery");
// const E = require('jquery.easing');
const B = require("bootstrap");
