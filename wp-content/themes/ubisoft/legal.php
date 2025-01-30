<?php
/*
 Template Name: Imprint
 */

get_header();

$address_1 = get_field('address_1');
$registered_office = get_field('registered_office');
$youth_protection_commissioner = get_field('youth_protection_commissioner');
$data_security_officer = get_field('data_security_officer');
$terms_of_use = get_field('terms_of_use');
$privacy_policy = get_field('privacy_policy');
$ceo = get_field('ceo');
?>

<div style="background: white">
  <section class="imprint-hero">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h2 class="h1">Imprint</h2>
        </div>
      </div>
    </div>
  </section>

  <?php
        while (have_posts()) :
            the_post();

            get_template_part('template-parts/content', get_post_type());

        endwhile; // End of the loop.
  ?>

  <?php if (!empty($address_1)) : ?>
    <section class="imprint-addresses">
      <div class="container">
        <div class="row">
          <div class="col-md-4 imprint-address-column">
            <?php if (isset($address_1['title'])): ?><div class="cta"><?php echo $address_1['title'] ?></div><?php endif; ?>

            <div class="row">
              <div class="col-6">
                <?php if (isset($address_1['company_name'])): ?><?php echo $address_1['company_name'] ?><br /><?php endif; ?>
                <?php if (isset($address_1['address_1'])): ?><?php echo $address_1['address_1'] ?><br /><?php endif; ?>
                <?php if (isset($address_1['address_2'])): ?><?php echo $address_1['address_2'] ?><?php endif; ?>
              </div>
              <div class="col-6">
                <p>
                  <?php if (isset($address_1['telephone'])): ?><span>T</span> <?php echo $address_1['telephone'] ?><br /><?php endif; ?>
                  <?php if (isset($address_1['fax'])): ?><span>F</span> <?php echo $address_1['fax'] ?><?php endif; ?>
                </p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <?php if (isset($address_1['contact_title'])): ?><div class="cta"><?php echo $address_1['contact_title'] ?></div><?php endif; ?>
            <?php if (isset($address_1['email'])): ?><p><span>E</span> <a href="mailto:<?php echo $address_1['email'] ?>"><?php echo $address_1['email'] ?></a></p><?php endif; ?>
          </div>
        </div>
      </div>
    </section>
  <?php endif ?>

  <?php if (!empty($registered_office)): ?>
    <section class="imprint-content">
      <div class="container">
        <div class="row">
          <div class="col-md-4">
            <div class="cta"><?php echo $registered_office['title'] ?></div>
            <?php echo $registered_office['text'] ?>
          </div>

          <div class="col-md-4">
            <div class="cta"><?php echo $youth_protection_commissioner['title'] ?></div>
            <?php echo $youth_protection_commissioner['text'] ?>
            <img style="margin-top: -25px; margin-bottom: 25px; max-width: 100px;" src="<?php echo get_template_directory_uri() ?>/assets/images/usk.png" alt="Unterhaltungssoftware Selbstkontrolle" />

            <div class="cta"><?php echo $data_security_officer['title'] ?></div>
            <?php echo $data_security_officer['text'] ?>

            <div class="cta"><?php echo $terms_of_use['title'] ?></div>
            <?php echo $terms_of_use['text'] ?>
          </div>

          <div class="col-md-4">
            <div class="cta"><?php echo $privacy_policy['title'] ?></div>
            <?php echo $privacy_policy['text'] ?>

            <div class="cta"><?php echo $ceo['title'] ?></div>
            <?php echo $ceo['text'] ?>
          </div>
        </div>
      </div>
    </section>
  <?php endif ?>
</div>

<?php
get_sidebar();
get_footer();
