<div class="container">
    <div class="row clearfix">
        <div class="col-md-12 column">
            <?php if ( isset( $_error ) && !empty( $_error ) ): ?>
                <div>
                    <p><?= $_error ?></p>
                </div>
            <?php elseif ( isset( $_success ) && !empty( $_success ) ): ?>
                <div>
                    <p><?= $_success ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="container">
    <div class="row clearfix">
        <div class="col-md-6 column">
            <h3>Add new professional experience</h3>
            <?php
            //Start the form
            $attributes = array( 'class' => 'experiences-form', 'id' => 'experience-add' );
            $action = 'experiences/add/';
            echo form_open_multipart( $action, $attributes );
            ?>
            <?php #region jobTitle
            $id = 'JobTitles_idJobTitles';
            $label = 'Job Title: ';
            echo form_label( $label, $id );
            echo form_dropdown( $id, $_jobTitleOptions );
            #endregion
            ?>
            <br/>
            <?php #region Other job titles
            $id = 'employerName';
            $label = 'Employer Name: ';
            $data = array(
                'name' => $id,
                'id' => $id,
                'maxlength' => 45,
            );
            echo form_label( $label, $id );
            echo form_input( $data );
            #endregion
            ?>
            <br/>
            <?php #region Date started
            $id = 'dateStarted';
            $label = 'Date Started: ';
            $data = array(
                'name' => $id,
                'id' => $id,
                'class' => 'datepicker'
            );
            echo form_label( $label, $id );
            echo form_input( $data );
            #endregion
            ?>
            <br/>
            <?php #region Date finished
            $id = 'dateFinished';
            $label = 'Date Finished: ';
            $data = array(
                'name' => $id,
                'id' => $id,
                'class' => 'datepicker'
            );
            echo form_label( $label, $id );
            echo form_input( $data );
            #endregion
            ?>
            <br/>

            <?php #region Other job titles
            $id = 'otherJobTitle';
            $label = 'Other Job Title: ';
            $data = array(
                'name' => $id,
                'id' => $id,
            );
            echo form_label( $label, $id );
            echo form_input( $data );
            #endregion
            ?>
            <br/>

            <?php #region Key duties
            $id = 'keyDuties';
            $label = 'Key Duties: ';
            $data = array(
                'name' => $id,
                'id' => $id
            );
            echo form_label( $label, $id );
            echo form_textarea( $data );
            #endregion
            ?>
            <br/>

            <?php #region Employement level
            $id = 'EmploymentLevels_idLevelsOfEmployment';
            $label = 'Employment Level: ';
            echo form_label( $label, $id );
            echo form_dropdown( $id, $_employmentLevelOptions );
            #endregion
            ?>
            <br/>

            <?php
            echo form_submit( 'submit-experience', 'Add', "class=btn btn-success btn-large" );
            ?>
            <?= form_close(); ?>

        </div>
        <div class="col-md-6 column">
            <h3>Current job experiences</h3>

            <?php if ( !isset( $_experiences ) || empty( $_experiences ) ) : ?>
                <p>You have no experiences added. spanPlease use the form to start adding.</p>
            <?php else : ?>
                <ol>
                    <?php foreach ( $_experiences as $_experience ): ?>
                        <li>
                            <p><?= $_experience->getExperienceName(); ?> <a
                                    href="<?= site_url( 'experiences/delete/' . $_experience->getExperienceId() ) ?>">(x)</a>

                            <div>
                                <ul>
                                    <?php $var = $_experience->getOtherJobTitle() ?>
                                    <?php if ( !empty( $var ) ) : ?>
                                        <li>
                                            Other job title: <?= $var ?>
                                        </li>
                                        <?php unset( $var ); endif; ?>
                                    <?php $var = $_experience->getKeyDuties() ?>
                                    <?php if ( !empty( $var ) ) : ?>
                                        <li>
                                            Key duties: <?= $var ?>
                                        </li>
                                        <?php unset( $var ); endif; ?>
                                    <?php $var = $_experience->getEmploymentLevel() ?>
                                    <?php if ( !empty( $var ) ) : ?>
                                        <li>
                                            Employment level: <?= $var ?>
                                        </li>
                                        <?php unset( $var ); endif; ?>
                                    <?php $var = $_experience->getEmployerName() ?>
                                    <?php if ( !empty( $var ) ) : ?>
                                        <li>
                                            Employer name: <?= $var ?>
                                        </li>
                                        <?php unset( $var ); endif; ?>
                                    <li>
                                        Verified: <?= $_experience->getVerified() ? 'Yes' : 'No' ?>
                                    </li>
                                    <?php $var = $_experience->getHowVerified() ?>
                                    <?php if ( !empty( $var ) ) : ?>
                                        <li>
                                            How verified: <?= $var ?>
                                        </li>
                                        <?php unset( $var ); endif; ?>
                                </ul>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ol>
            <?php endif; ?>
        </div>
    </div>
</div>
