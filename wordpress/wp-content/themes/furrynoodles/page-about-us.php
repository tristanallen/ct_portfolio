<?php
/**
 * The about us page template file
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package furrynoodles
 * @subpackage wordpress_site
 * @since Furrynoodles 1.0
 */
get_header(); ?>

<div class="introduction">
  <p><?php echo get_bloginfo ( 'description' ) ?></p>
    <!--img src="../wp-content/themes/furrynoodles/img/about_us_photo_olde2.jpg" /-->
</div>
<section>
  <div class="about-us">
    <div class="people clear">
      <div class="person">
        <h3>Clarence Lee</h3>
        <p>Clarence has managed digital teams at Time Out, led UX projects at Tobias & Tobias, and had illustrations published on the Playstation website.</p>
      </div>
      <div class="person">
        <h3>Tristan Allen</h3>
        <p>Tristan has worked for Global Interactive Marketing Online, HMX with such clients as Sony, Lenovo, Dell, Carphone Warehouse, panasonic, Nikon</p>
      </div>
      <div class="skills">
        <ul>
          <li>Visual Design</li>
          <li>User Experience</li>
          <li>HTML / CSS</li>
          <li>PHP / SQL</li>
          <li>iOS</li>
        </ul>
        <ul>
          <li>Development</li>
          <li>Flash</li>
          <li>HTML / CSS</li>
          <li>PHP / SQL</li>
          <li>Android</li>
        </ul>
      </div>
    </div>
    <div class="services-skills clear">
      <ul>
        <li>Websites</li>
        <li>Webapps</li>
        <li>Mobile Apps (iOS/Android)</li>
        <li>HTML Emails</li>
      </ul>
    </div>
  </div>
</section>

<?php get_footer(); ?>
