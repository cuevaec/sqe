<?php /** @var $_jobseeker Jobseeker */?>
<div class="container">
    <div class="row clearfix">
        <div class="col-md-2 column">
            <img alt="140x140" src="http://lorempixel.com/140/140/" class="img-circle"/>
        </div>
        <div class="col-md-10 column">
            <h3 class="text-left">
                <?= $_jobseeker->getFullName(); ?>
            </h3>

            <p>
                <?= $_jobseeker->getAuthorityToWorkStatement(); ?>
            </p>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-md-4 column">
            <address>
                <?= $_jobseeker->getFullAddress(); ?><br/>---
                <br/><abbr title="Email">E:</abbr> <?= $_jobseeker->getUsername(); ?>
                <?php if ( !empty( $email2 = $_jobseeker->getSecondEmail() ) && $email2 != $_jobseeker->getUsername() ) : ?>
                    <br/><abbr title="Email2">E:</abbr> <?= $email2; ?>
                <?php endif; ?>
                <?php if ( !empty( $landline = $_jobseeker->getLandline() ) ) : ?>
                    <br/><abbr title="Phone">P:</abbr> <?= $landline; ?>
                <?php endif; ?>
                <?php if ( !empty( $mobile = $_jobseeker->getMobile() ) ) : ?>
                    <br/><abbr title="Mobile">M:</abbr> <?= $mobile; ?>
                <?php endif; ?>
                <?php if ( !empty( $personalUrl = $_jobseeker->getPersonalUrl() ) ) : ?>
                    <br/><abbr title="Web">W:</abbr> <?= $personalUrl; ?>
                <?php endif; ?>
            </address>
        </div>
        <div class="col-md-4 column">
            <ul>
                <?php if ( !empty( $contactPreference = $_jobseeker->getContactPreference() ) ) : ?>
                    <li>
                        Contact preference: <?=$contactPreference?>
                    </li>
                <?php endif; ?>
                <?php if ( !empty( $educationLevel = $_jobseeker->getEducationLevel() ) ) : ?>
                    <li>
                        Education level: <?=$educationLevel?>
                    </li>
                <?php endif; ?>
                <?php if ( !empty( $noOfGcses = $_jobseeker->getNoOfGCses() ) ) : ?>
                    <li>
                        No of GCSEs: <?=$noOfGcses?>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="col-md-4 column">
            <ol>
                <li>
                    Lorem ipsum dolor sit amet
                </li>
                <li>
                    Consectetur adipiscing elit
                </li>
                <li>
                    Integer molestie lorem at massa
                </li>
                <li>
                    Facilisis in pretium nisl aliquet
                </li>
                <li>
                    Nulla volutpat aliquam velit
                </li>
                <li>
                    Faucibus porta lacus fringilla vel
                </li>
                <li>
                    Aenean sit amet erat nunc
                </li>
                <li>
                    Eget porttitor lorem
                </li>
            </ol>
        </div>
    </div>
</div>
